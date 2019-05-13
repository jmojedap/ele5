<?php

//Definición del enunciado
    $key_opcion = 0;
    $opciones_numeros = array(1,2,3,4);
    
    shuffle($opciones_numeros); //Se desordenan las opciones
    $num_respondidas_mas = $row->num_con_respuesta + 1;
    $num_respondidas_menos = $row->num_con_respuesta;
    
    $opciones_letras = array(
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
    );
    
    
//Valores iniciales
    $respuesta_actual = $row_pregunta->respuesta_correcta;
    $num_preguntas = $this->Pcrn->no_cero($row->num_preguntas);

//Verificar si está respondida    
    if ( ! is_null($row_respuesta) ){
        $respuesta_actual = $row_respuesta->respuesta;
    }
    
//Si ya está respondida
    if ( $respuesta_actual > 0 )
    { 
        $num_respondidas_mas = $row->num_con_respuesta;
        $num_respondidas_menos = $row->num_con_respuesta - 1;
    }
    
    $porcentaje_inicial = number_format(100 * $row->num_con_respuesta / $num_preguntas);
    $porcentaje_mas = number_format(100 * $num_respondidas_mas / $num_preguntas);
    $porcentaje_menos = number_format(100 * $num_respondidas_menos / $num_preguntas);
    
    //Cantidad de preguntas sin respuesta
    $sin_respuesta = $row->num_preguntas - $row->num_con_respuesta;
    
//Imagen pregunta: pregunta.archivo_imagen
    $src_alt = URL_IMG . "app/img_pregunta_nd.png";   //Imagen alternativa
    
    $att_img['src'] = RUTA_UPLOADS .  "preguntas/" .$row_pregunta->archivo_imagen;
    $att_img['width'] = '100%';
    $att_img['style'] = 'max-width: 800px';
    $att_img['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
            
//Imagen enunciado: enunciado.archivo_imagen
    $att_img_enunciado['src'] = URL_UPLOADS . 'enunciados/' . $row_enunciado->texto_2;
    $att_img_enunciado['style'] = 'max-width: 600px; max-height: 600px;';
    //$att_img_enunciado['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
            
//Navegar entre preguntas
    $num_pregunta_ant = $this->Pcrn->rotar_entre($num_pregunta - 1, 1, $num_preguntas);
    $num_pregunta_sig = $this->Pcrn->rotar_entre($num_pregunta + 1, 1, $num_preguntas);
?>

<div class="mb-3">
    <!-- Split button -->
    <div class="btn-group">
        <button type="button" class="btn btn-info">
            <i class="fa fa-print"></i> 
            Imprimir
        </button>
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <?php echo anchor("cuestionarios/imprimir/{$row->id}", 'Cuestionario', 'title="Imprimir cuestionario" target="_blank" class="dropdown-item"') ?>
            </li>
            <li>
                <?php echo anchor("cuestionarios/imprimir/{$row->id}/respuestas", 'Respuestas', 'title="Hoja de respuestas cuestionario" target="_blank" class="dropdown-item"') ?>
            </li>
        </ul>
    </div>
    
    <?php if ( $convertible ) { ?>
        <?php echo $this->Pcrn->anchor_confirm("cuestionarios/convertir/{$row->id}", '<i class="fa fa-pencil-square-o"></i> Convertir en editable', 'class="btn btn-secondary" title="Convertir en un cuestionario simple, para editar preguntas."', '¿Confirma que quiere convertir este cuestionario en Editable?') ?>
    <?php } ?>
</div>


<link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/apanel3/cuestionario.css">

<script>
    
//FUNCIONES
//------------------------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var uc_id = <?php echo $row->id ?>;
    var usuario_id = <?php echo $row->usuario_id ?>;
    var pregunta_id = <?php echo $row_pregunta->id ?>;
    var cuestionario_id = <?php echo $row->cuestionario_id ?>;
    
    var num_preguntas = <?php echo $num_preguntas ?>;
    var num_respondidas = <?php echo $row->num_con_respuesta ?>;
    var num_respondidas_mas = <?php echo $num_respondidas_mas ?>;
    var num_respondidas_menos = <?php echo $num_respondidas_menos ?>;
    var sin_repuesta = <?php echo $sin_respuesta ?>;
    
    var porcentaje = <?php echo $porcentaje_inicial ?>;
    var porcentaje_mas = <?php echo $porcentaje_mas ?>;
    var porcentaje_menos = <?php echo $porcentaje_menos ?>;
    var respuesta = 0;

//DOCUMENT READY
//------------------------------------------------------------------------------------------
    
    $(document).ready(function(){
        //Cuenta regresiva
        //$('#the_final_countdown_v4').countdown({until: +<?php echo $segundos_restantes ?>, format: 'HMS'});
        //Fin de cuenta regresiva
        
        /**
         * Al hacer clic en una de las opciones de respuesta
         * @returns {undefined}
         */
        /*$('.opcion_respuesta').click(function(){
            $('.opcion_respuesta').removeClass('opcion_seleccionada');
            $(this).addClass('opcion_seleccionada');
            respuesta = $(this).data('respuesta');
            guardar_respuesta();
            $('#borrar_repuesta').show();
            
            num_respondidas = num_respondidas_mas;
            porcentaje = porcentaje_mas;
            
            actualizar_numeros();
        });*/
        
        /**
         * Al presional el botón [Borrar respuesta]
         */
        /*$('#borrar_respuesta').click(function(){
            $('.opcion_respuesta').removeClass('opcion_seleccionada');
            respuesta = 0;
            guardar_respuesta();
            
            num_respondidas = num_respondidas_menos;
            porcentaje = porcentaje_menos;
            actualizar_numeros();
        });*/
        
    });
    
//FUNCIONES
//------------------------------------------------------------------------------------------

    //Ajax
    function guardar_respuesta(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'cuestionarios/guardar_respuesta_ajax/' + uc_id,
            data: {
                usuario_id : usuario_id,
                pregunta_id : pregunta_id,
                cuestionario_id : cuestionario_id,
                respuesta : respuesta
            },
            success: function(respuesta){
                $('#borrar_repuesta').removeClass('hidden');
            }
        });
    }
    
    function actualizar_numeros()
    {
        sin_respuesta = num_preguntas - num_respondidas;
        $('#barra_porcentaje').css('width', porcentaje + '%');
        $('#barra_porcentaje').html(porcentaje + '%');
        
        $('#num_respondidas').html(num_respondidas);
        $('#sin_respuesta').html(sin_respuesta);
        $('#sin_respuesta_mensaje').html(sin_respuesta);
    }
    
</script>

<div class="row">
    
    <div class="col-md-8">
        
        <div class="mb-3">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#tab_pregunta" aria-controls="home" role="tab" data-toggle="tab" class="nav-link active">
                        Pregunta <?php echo $num_pregunta; ?>
                    </a>
                </li>
                
                <?php if ( ! is_null($row_enunciado) ) { ?>
                    <li role="presentation" class="nav-item">
                        <a class="nav-link" href="#tab_enunciado" aria-controls="profile" role="tab" data-toggle="tab">
                            <span class="badge badge-danger">
                                <i class="fa fa-caret-right"></i> Lectura
                            </span>
                        </a>
                    </li>
                <?php } ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabcard" class="tab-pane active" id="tab_pregunta">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col col-sm-6">
                                <?php echo anchor("cuestionarios/vista_previa/{$row->id}/{$num_pregunta_ant}", '<i class="fa fa-arrow-left"></i>', 'class="btn btn-secondary w3" title="Pregunta anterior"') ?>
                                <?php echo anchor("cuestionarios/vista_previa/{$row->id}/{$num_pregunta_sig}", '<i class="fa fa-arrow-right"></i>', 'class="btn btn-secondary w3" title="Siguiente pregunta"') ?>
                            </div>
                            <div class="col col-sm-6">
                                <div class="btn btn-warning float-right" id="borrar_respuesta">
                                    Borrar respuesta
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-default">
                        <div class="card-body">

                            <div class="mb-3">
                                <p style="font-size: 1.1em;"><?php echo $row_pregunta->texto_pregunta ?></p>
                            </div>

                            <div class="mb-3">

                                <?php foreach ($opciones_numeros as $opcion_numero) : ?>
                                    <?php
                                        $key_opcion++;
                                        $clase_opcion = '';
                                        if ( $respuesta_actual == $opcion_numero ) { $clase_opcion = 'opcion_correcta'; }
                                        $campo = 'opcion_' . $opcion_numero;
                                    ?>
                                    <div class="card card-default mb-3 opcion_respuesta <?php echo $clase_opcion ?>" data-respuesta="<?php echo $opcion_numero ?>">
                                        <div class="card-body">
                                            <?php if ( strlen($row_pregunta->$campo) > 0 ) { ?>
                                                <span class="badge badge-primary informacion w1">
                                                    <?php echo $opciones_letras[$key_opcion] ?>
                                                </span>
                                                <?php echo $row_pregunta->$campo ?>
                                                
                                            <?php } else { ?>
                                                <span class="badge badge-primary w1">
                                                    <?php echo $opciones_letras[$opcion_numero]; ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                <?php endforeach ?>
                                
                            </div>
                            
                            <!-- Mostrar imagen si la pregunta tiene respuestas en imagen -->
                            <?php if ( strlen($row_pregunta->archivo_imagen) > 0 ):?>
                                <div class="mb-3 text-center">
                                    <div class="thumbnail">
                                        <?php echo img($att_img) ?>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>


                    </div>
                </div>
                
                <div role="tabcard" class="tab-pane" id="tab_enunciado">
                    <!--  Mostar Enunciado si la pregunta tiene uno asignado -->
                    <?php if ( $row_enunciado != NULL ){?>
                        <div class="card card-default">
                            <div class="card-heading">
                                <?php echo $row_enunciado->nombre_post ?>
                            </div>
                            <div class="card-body">
                                <?php echo $row_enunciado->contenido ?>
                                <?php if ( strlen($row_enunciado->texto_2) > 0 ){ ?>
                                    <hr/>
                                    <div>
                                        <div style="margin: 0 auto; max-width: 600px; max-height: 600px;">
                                            <?php echo img($att_img_enunciado) ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                
            </div>

        </div>
        
    </div>
    
    <div class="col-md-4">
        <div class="card card-default">
            <div class="card-body">
                <div id="the_final_countdown_v4"></div>
                <div class="progress mb-3">
                    <div id="barra_porcentaje" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo '30' ?>%;">
                        <?php echo '30' ?>%
                    </div>
                </div>

                <div class="mb-3">
                    <?php foreach ($arr_respuestas as $key => $respuesta) { ?>
                        <?php
                            $i = $key + 1;
                            $clase = 'btn btn-sm btn-secondary';
                            if ( $respuesta > 0 ) { $clase = 'btn btn-sm btn-info'; }
                            if ( $i == $num_pregunta ) { $clase = 'btn btn-sm btn-warning '; }
                            
                        ?>
                        <?php echo anchor("cuestionarios/vista_previa/{$row->id}/{$i}/", $i, 'class="' . $clase . '" style="width: 35px; margin-bottom: 2px;"') ?>
                    <?php } ?>
                </div>
                
                <div class="mb-3 hidden-xs">
                    <ul class="list-group">
                        <li class="list-group-item">
                          <span class="badge badge-secondary"><?php echo $row->num_preguntas ?></span>
                          Preguntas
                        </li>
                        <li class="list-group-item">
                          <span class="badge badge-secondary" id="num_respondidas">0</span>
                          Respondidas
                        </li>
                        <li class="list-group-item" id="num_sin_respuesta">
                          <span class="badge badge-secondary" id="sin_respuesta"><?php echo $sin_respuesta ?></span>
                          Sin responder
                        </li>
                  </ul>
                </div>
                
                <div class="">
                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#confirmar_finalizar">
                        Finalizar
                    </button>
                </div>
            </div>
            
        </div>
    </div>
    
    
</div>