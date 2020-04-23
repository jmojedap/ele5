<?php
    $opciones_numeros = array(1,2,3,4);
    $opciones_letras = array(
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
    );

    $cl_difficulty_level = array('bg-light', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger');
    //shuffle($opciones_numeros); //Se desordenan las opciones
    
    //Imagen pregunta: pregunta.archivo_imagen
        $src_alt = URL_IMG . "app/img_pregunta_nd.png";   //Imagen alternativa

        $att_img['src'] = URL_UPLOADS .  "preguntas/" .$row_pregunta->archivo_imagen;
        $att_img['width'] = '100%';
        $att_img['style'] = 'max-width: 800px';
        $att_img['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
        
    //Nombre y link de Enunciado
        $nombre_enunciado = 'No';
        if ( strlen($row_pregunta->enunciado_id) > 0 ) 
        {
            $nombre_enunciado = anchor("datos/enunciados_ver/{$row_pregunta->enunciado_id}", $this->App_model->nombre_enunciado($row_pregunta->enunciado_id));
        }
?>

<link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES . 'templates/apanel3/cuestionario.css' ?>">

<div class="row">
    <div class="col col-md-8">
        <div class="card card-default">
            <div class="card-body">
                <p style="font-size: 1.1em;"><?php echo $row_pregunta->texto_pregunta ?></p>
                <p style="font-size: 1.1em;"><?php echo $row_pregunta->enunciado_2 ?></p>

                <div class="mb-2">

                    <?php foreach ($opciones_numeros as $opcion_numero) : ?>
                        <?php
                            $clase_opcion = '';
                            if ( $row_pregunta->respuesta_correcta == $opcion_numero ) { $clase_opcion = 'opcion_seleccionada'; }
                            $campo = 'opcion_' . $opcion_numero;
                        ?>
                        <div class="card mb-2 opcion_respuesta <?php echo $clase_opcion ?>" data-respuesta="<?php echo $opcion_numero ?>">
                            <div class="card-body">
                                <b>
                                    [<?php echo $opciones_letras[$opcion_numero] ?>]
                                </b>
                                <?php echo $row_pregunta->$campo ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                
                <!-- Mostrar imagen si la pregunta tiene respuestas en imagen -->
                <?php if ( strlen($row_pregunta->archivo_imagen) > 0 ):?>
                    <div class="mb-2 text-center">
                        <div class="thumbnail">
                            <?php echo img($att_img) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        Sin imagen asignada
                    </div>
                <?php endif ?>

            </div>
        </div>

        <?php if ( ! is_null($row_enunciado) ) { ?>
            <div class="card">
                <div class="card-header">
                    Lectura asociada
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col col-sm-6">
                            <h3><?php echo $row_enunciado->nombre_post ?></h3>
                            <p class="suave">Post ID: <?php echo $row_enunciado->id ?></p>

                            <?php echo $row_enunciado->contenido ?>
                        </div>
                        <div class="col col-sm-6">
                            <?php if ( strlen($row_enunciado->texto_2) > 0 ) { ?>
                                <?php
                                    $src = URL_UPLOADS . $row_enunciado->texto_3 . $row_enunciado->texto_2;
                                ?>

                                <?php echo img($src) ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col col-md-4">
        <div class="card card-default">
            <div class="card-header">
                Información de la pregunta
            </div>
            <div class="card-body">
                <dl class="dl-horizontal">
                    <dt>Estadísticas</dt>
                    <dd>
                        Respondida <?php echo $row->qty_answers; ?> veces, <?php echo $row->qty_right; ?> correctas
                    </dd>
                    <dt>Dificultad</dt>
                    <dd>
                        <div class="progress">
                            <div class="progress-bar <?php echo $cl_difficulty_level[$row->difficulty_level] ?>" role="progressbar" style="width: <?php echo $row->difficulty ?>%" aria-valuenow="<?php echo $row->difficulty ?>" aria-valuemin="0" aria-valuemax="100">
                                <?php echo $row->difficulty ?>
                                &middot;
                                <?php echo $this->Item_model->nombre(158, $row->difficulty_level); ?>
                            </div>
                        </div>
                    </dd>
                    <dt>Palabras clave</dt>
                    <dd>
                        <?php echo $row->palabras_clave ?>
                    </dd>
                    <dt>Tema</dt>
                    <dd>
                        <?php echo $this->App_model->nombre_tema($row->tema_id) ?>
                    </dd>
                    
                    <dt>Lectura complementaria</dt>
                    <dd>
                        <?php if ( $row_pregunta->enunciado_id > 0 ) { ?>
                            <a href="<?php echo base_url("datos/enunciados_ver/{$row->enunciado_id}") ?>" target="_blank">
                                <?php echo $this->App_model->nombre_enunciado($row_pregunta->enunciado_id) ?>
                            </a>
                        <?php } else { ?>
                            No tiene lectura complementaria
                        <?php } ?>
                    </dd>
                    
                    <dt>Componente</dt>
                    <dd>
                        <?php echo $this->Item_model->nombre_id($row_pregunta->componente_id) ?>
                    </dd>
                    
                    <dt>Competencia</dt>
                    <dd>
                        <?php echo $this->Item_model->nombre_id($row_pregunta->competencia_id) ?>
                    </dd>
                    
                    <dt>Editado</dt>
                    <dd>
                        <?php echo $this->App_model->nombre_usuario($row->editado_usuario_id, 2) ?> - 
                        <?php echo $this->Pcrn->fecha_formato($row->editado) ?>
                    </dd>
                    
                    <dt>Creado</dt>
                    <dd>
                        <?php echo $this->App_model->nombre_usuario($row->creado_usuario_id, 2) ?> - 
                        <?php echo $this->Pcrn->fecha_formato($row->creado) ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

