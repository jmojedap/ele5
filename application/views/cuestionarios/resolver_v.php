<?php

//Definición del enunciado
    $key_opcion = 0;
    $opciones_numeros = array(1,2,3,4);
    
    shuffle($opciones_numeros); //Se desordenan las opciones
    $num_respondidas_mas = $row_uc->num_con_respuesta + 1;
    $num_respondidas_menos = $row_uc->num_con_respuesta;
    
    $opciones_letras = array(
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
    );
    
    
//Valores iniciales
    $respuesta_actual = 0;
    $num_preguntas = $this->Pcrn->no_cero($row->num_preguntas);

//Verificar si está respondida    
    if ( ! is_null($row_respuesta) ){
        $respuesta_actual = $row_respuesta->respuesta;
    }
    
//Si ya está respondida
    if ( $respuesta_actual > 0 )
    { 
        $num_respondidas_mas = $row_uc->num_con_respuesta;
        $num_respondidas_menos = $row_uc->num_con_respuesta - 1;
    }
    
    $porcentaje_inicial = number_format(100 * $row_uc->num_con_respuesta / $num_preguntas);
    $porcentaje_mas = number_format(100 * $num_respondidas_mas / $num_preguntas);
    $porcentaje_menos = number_format(100 * $num_respondidas_menos / $num_preguntas);
    
    //Cantidad de preguntas sin respuesta
    $sin_respuesta = $row->num_preguntas - $row_uc->num_con_respuesta;
    
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

<link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>plantillas/apanel2/cuestionario.css">

<script>
    
//FUNCIONES
//------------------------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var uc_id = <?= $row_uc->id ?>;
    var usuario_id = <?= $row_uc->usuario_id ?>;
    var pregunta_id = <?= $row_pregunta->id ?>;
    var cuestionario_id = <?= $row_uc->cuestionario_id ?>;
    
    var num_preguntas = <?= $num_preguntas ?>;
    var num_respondidas = <?= $row_uc->num_con_respuesta ?>;
    var num_respondidas_mas = <?= $num_respondidas_mas ?>;
    var num_respondidas_menos = <?= $num_respondidas_menos ?>;
    var sin_repuesta = <?= $sin_respuesta ?>;
    
    var porcentaje = <?= $porcentaje_inicial ?>;
    var porcentaje_mas = <?= $porcentaje_mas ?>;
    var porcentaje_menos = <?= $porcentaje_menos ?>;
    var respuesta = 0;

//DOCUMENT READY
//------------------------------------------------------------------------------------------
    
    $(document).ready(function(){
        //Cuenta regresiva
        $('#the_final_countdown_v4').countdown({until: +<?php echo $segundos_restantes ?>, format: 'HMS'});
        //Fin de cuenta regresiva
        
        /**
         * Al hacer clic en una de las opciones de respuesta
         * @returns {undefined}
         */
        $('.opcion_respuesta').click(function(){
            $('.opcion_respuesta').removeClass('opcion_seleccionada');
            $(this).addClass('opcion_seleccionada');
            respuesta = $(this).data('respuesta');
            guardar_respuesta();
            $('#borrar_repuesta').show();
            
            num_respondidas = num_respondidas_mas;
            porcentaje = porcentaje_mas;
            
            actualizar_numeros();
        });
        
        /**
         * Al presional el botón [Borrar respuesta]
         */
        $('#borrar_respuesta').click(function(){
            $('.opcion_respuesta').removeClass('opcion_seleccionada');
            respuesta = 0;
            guardar_respuesta();
            
            num_respondidas = num_respondidas_menos;
            porcentaje = porcentaje_menos;
            actualizar_numeros();
        });
        
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

<div class="row hidden-xs">
    <div class="col-md-12">
        
        <h4><?= $this->App_model->nombre_usuario($row_uc->usuario_id, 2) ?></h4>
        <p>
            <span class="suave">Hora inicio: </span>
            <span class="resaltar"><?= $this->Pcrn->fecha_formato($row_uc->inicio_respuesta, 'M-d h:i a') ?></span> | 
            <span class="suave">Hora fin: </span>
            <span class="resaltar"><?= $hora_final ?></span> |
        </p>
    </div>
</div>

<div class="row">
    
    <div class="col-md-8">
        
        <div class="sep1">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs sep1" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tab_pregunta" aria-controls="home" role="tab" data-toggle="tab">
                        Pregunta <?= $num_pregunta; ?>
                    </a>
                </li>
                
                <?php if ( ! is_null($row_enunciado) ) { ?>
                    <li role="presentation" class="">
                        <a href="#tab_enunciado" aria-controls="profile" role="tab" data-toggle="tab">
                            <span class="label label-danger">
                                <i class="fa fa-caret-right"></i> Lectura
                            </span>
                        </a>
                    </li>
                <?php } ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tab_pregunta">
                    <div class="sep1">
                        <div class="row">
                            <div class="col col-sm-6">
                                <?= anchor("cuestionarios/resolver/{$row_uc->id}/{$num_pregunta_ant}", '<i class="fa fa-arrow-left"></i>', 'class="btn btn-default w3" title="Pregunta anterior"') ?>
                                <?= anchor("cuestionarios/resolver/{$row_uc->id}/{$num_pregunta_sig}", '<i class="fa fa-arrow-right"></i>', 'class="btn btn-default w3" title="Siguiente pregunta"') ?>
                            </div>
                            <div class="col col-sm-6">
                                <div class="btn btn-default pull-right" id="borrar_respuesta">
                                    Borrar respuesta
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="sep1">
                                <p style="font-size: 1.1em;"><?= $row_pregunta->texto_pregunta ?></p>
                            </div>

                            <div class="sep1">

                                <?php foreach ($opciones_numeros as $opcion_numero) : ?>
                                    <?php
                                        $key_opcion++;
                                        $clase_opcion = '';
                                        if ( $respuesta_actual == $opcion_numero ) { $clase_opcion = 'opcion_seleccionada'; }
                                        $campo = 'opcion_' . $opcion_numero;
                                    ?>
                                    <div class="panel panel-default opcion_respuesta <?= $clase_opcion ?>" data-respuesta="<?= $opcion_numero ?>">
                                        <div class="panel-body">
                                            <?php if ( strlen($row_pregunta->$campo) > 0 ) { ?>
                                                <span class="etiqueta informacion w1">
                                                    <?= $opciones_letras[$key_opcion] ?>
                                                </span>
                                                <?= $row_pregunta->$campo ?>
                                                
                                            <?php } else { ?>
                                                <span class="etiqueta informacion w1">
                                                    <?= $opciones_letras[$opcion_numero]; ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                <?php endforeach ?>
                                
                            </div>
                            
                            <!-- Mostrar imagen si la pregunta tiene respuestas en imagen -->
                            <?php if ( strlen($row_pregunta->archivo_imagen) > 0 ):?>
                                <div class="sep1 text-center">
                                    <div class="thumbnail">
                                        <?= img($att_img) ?>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>


                    </div>
                </div>
                
                <div role="tabpanel" class="tab-pane" id="tab_enunciado">
                    <!--  Mostar Enunciado si la pregunta tiene uno asignado -->
                    <?php if ( $row_enunciado != NULL ){?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?= $row_enunciado->nombre_post ?>
                            </div>
                            <div class="panel-body">
                                <?= $row_enunciado->contenido ?>
                                <?php if ( strlen($row_enunciado->texto_2) > 0 ){ ?>
                                    <hr/>
                                    <div>
                                        <div style="margin: 0 auto; max-width: 600px; max-height: 600px;">
                                            <?= img($att_img_enunciado) ?>
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
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="the_final_countdown_v4"></div>
                <div class="progress">
                    <div id="barra_porcentaje" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje_inicial ?>%;">
                        <?= $porcentaje_inicial ?>%
                    </div>
                </div>
                <?php foreach ($arr_respuestas as $key => $respuesta) { ?>
                    <?php
                        $i = $key + 1;
                        $clase = 'btn btn-sm btn-default';
                        if ( $respuesta > 0 ) { $clase = 'btn btn-sm btn-info'; }
                        if ( $i == $num_pregunta ) { $clase = 'btn btn-sm btn-warning '; }
                        
                    ?>
                    <?= anchor("cuestionarios/resolver/{$row_uc->id}/{$i}/", $i, 'class="' . $clase . '" style="width: 35px; margin-bottom: 2px;"') ?>
                <?php } ?>
                
                <div class="sep2 hidden-xs">
                    <ul class="list-group">
                        <li class="list-group-item">
                          <span class="badge"><?= $row->num_preguntas ?></span>
                          Preguntas
                        </li>
                        <li class="list-group-item">
                          <span class="badge" id="num_respondidas"><?= $row_uc->num_con_respuesta ?></span>
                          Respondidas
                        </li>
                        <li class="list-group-item" id="num_sin_respuesta">
                          <span class="badge badge-danger" id="sin_respuesta"><?= $sin_respuesta ?></span>
                          Sin responder
                        </li>
                    </ul>
                </div>
                
                <div class="sep2">
                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#confirmar_finalizar">
                        Finalizar
                    </button>
                </div>
            </div>
            
        </div>
    </div>
    
    
</div>

<?php //Ventana modal Confirma Finalizar ?>

<div class="modal fade" id="confirmar_finalizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Plataforma Enlace</h4>
            </div>
            <div class="modal-body" id="mensaje_confirmacion">
                Tiene <span id="sin_respuesta_mensaje"><?= $sin_respuesta ?></span> preguntas sin responder ¿Confirma la terminación del cuestionario?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <?= anchor("cuestionarios/finalizar/{$row_uc->id}", 'Finalizar', 'title="Finalizar cuestionario..." class="btn btn-primary"') ?>
            </div>
        </div>
    </div>
</div>