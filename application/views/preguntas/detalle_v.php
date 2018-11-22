<?php
    $opciones_numeros = array(1,2,3,4);
    $opciones_letras = array(
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
    );
    //shuffle($opciones_numeros); //Se desordenan las opciones
    
    //Imagen pregunta: pregunta.archivo_imagen
        $src_alt = URL_IMG . "app/img_pregunta_nd.png";   //Imagen alternativa

        $att_img['src'] = RUTA_UPLOADS .  "preguntas/" .$row_pregunta->archivo_imagen;
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

<link type="text/css" rel="stylesheet" href="<?= base_url('css/apanel2/cuestionario.css') ?>">

<div class="row">
    <div class="col col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <p style="font-size: 1.1em;"><?= $row_pregunta->texto_pregunta ?></p>

                <div class="sep1">

                    <?php foreach ($opciones_numeros as $opcion_numero) : ?>
                        <?php
                            $clase_opcion = '';
                            if ( $row_pregunta->respuesta_correcta == $opcion_numero ) { $clase_opcion = 'opcion_seleccionada'; }
                            $campo = 'opcion_' . $opcion_numero;
                        ?>
                        <div class="panel panel-default opcion_respuesta <?= $clase_opcion ?>" data-respuesta="<?= $opcion_numero ?>">
                            <div class="panel-body">
                                <b>
                                    [<?= $opciones_letras[$opcion_numero] ?>]
                                </b>
                                <?= $row_pregunta->$campo ?>
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
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        Sin imagen asignada
                    </div>
                <?php endif ?>

            </div>
        </div>

        <?php if ( ! is_null($row_enunciado) ) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Enunciado Relacionado
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col col-sm-6">
                            <h3><?= $row_enunciado->nombre_post ?></h3>
                            <p class="suave">Post ID: <?= $row_enunciado->id ?></p>

                            <?= $row_enunciado->contenido ?>
                        </div>
                        <div class="col col-sm-6">
                            <?php if ( strlen($row_enunciado->texto_2) > 0 ) { ?>
                                <?php
                                    $src = URL_UPLOADS . $row_enunciado->texto_3 . $row_enunciado->texto_2;
                                ?>

                                <?= img($src) ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Información de la pregunta
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt>Tema</dt>
                    <dd>
                        <?= $this->App_model->nombre_tema($row->tema_id) ?>
                    </dd>
                    
                    <dt>Enunciado adjunto</dt>
                    <dd>
                        <?= $nombre_enunciado ?>
                    </dd>
                    
                    <dt>Componente</dt>
                    <dd>
                        <?= $this->Item_model->nombre_id($row_pregunta->componente_id) ?>
                    </dd>
                    
                    <dt>Competencia</dt>
                    <dd>
                        <?= $this->Item_model->nombre_id($row_pregunta->competencia_id) ?>
                    </dd>
                    
                    <dt>Editado</dt>
                    <dd>
                        <?= $this->Pcrn->fecha_formato($row->editado) ?>
                    </dd>
                    
                    <dt>Por</dt>
                    <dd>
                        <?= $this->App_model->nombre_usuario($row->editado_usuario_id, 2) ?>
                    </dd>
                    
                    <dt>Creado</dt>
                    <dd>
                        <?= $this->Pcrn->fecha_formato($row->creado) ?>
                    </dd>
                    
                    <dt>Por</dt>
                    <dd>
                        <?= $this->App_model->nombre_usuario($row->creado_usuario_id, 2) ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

