<?php
    //Clase bookmark
        $clase_bookmark = 'btn-default';
        if ( $num_pagina == $bookmark ) { $clase_bookmark = 'btn-success'; }

    //Imagen
        $src_alt = URL_IMG . 'app/pf_nd_3.png';   //Imagen alternativa
        
        $att_img = array(
            'id'    => 'imagen_pagina',
            'onError' => "this.src='" . $src_alt . "'", //Imagen alternativa
            'class' =>    'pf_v4',
            'src' =>    $carpeta_uploads  . 'pf_zoom/' . $paginas->row($num_pagina)->archivo_imagen,
            'style' =>  'max-height: 800px; max-width: 100%;'
        );
        
    //Íconos
       $att_icono_quiz =  array(
           'src' => $carpeta_iconos . 'quices.png'
       );
        
    //Color fondo
        $colores = $this->App_model->arr_color_area();
        
    //Text anotaciones
        $att_anotacion = array(
            'id' => 'anotacion',
            'rows' => 10,
            'class' =>  'anotacion',
            'placeholder' =>  'Escriba una anotación sobre este tema'
        );
        
    //Para índice
        $tema_id_ant = 0;
?>

<body style="background: <?= $colores[$row->area_id] ?>; ">
    
    <div class="container">
        <!-- BARRA DE HERRAMIENTAS-->
        <div class="row">
            
            <div class="col-md-offset-2 col-md-7">

                <div class="text-center" style="min-height: 34px;">
                    <div class="w2 pull-left boton_fb btn btn-default" id="pagina_ant"><i class="fa fa-caret-left"></i></div>
                    
                    <div class="w2 boton_fb btn btn-default hidden-md hidden-lg" id="alternar_menu_recursos"><i class="fa fa-files-o"></i></div>
                    
                    <div class="w2 pull-right boton_fb btn btn-default" id="pagina_sig"><i class="fa fa-caret-right"></i></div>
                </div>
                
                <div id="slider"></div>
                
            </div>
            
            <div class="col-md-3"></div>
        </div>
        
        <!-- SECCIÓN CONTENIDO -->
        <div class="row seccion_contenido">
            
            <!-- SECCIÓN DE RECURSOS -->
            <div class="col-md-2">
                <div id="menu_recursos" class="hidden-xs hidden-sm">
                    
                    <!--QUICES-->
                    <a href="#listado_quices" data-toggle="collapse" aria-expanded="true" class="btn btn-default btn-block" style="margin-bottom: 5px;">
                        <img id="mostrar_quices" src="<?php echo URL_IMG ?>flipbook/quices_banner_v4.png">
                    </a>

                    <div id="listado_quices" class="collapse sep2">
                        <?php foreach ($quices->result() as $row_quiz) : ?>
                            <?php
                                $clase_pagina = 'pagina_' . $row_quiz->num_pagina;
                                $clases = "btn btn-default recurso " . $clase_pagina; 
                            ?>
                            <?= anchor("quices/iniciar/{$row_quiz->id}", img($att_icono_quiz), 'class="' . $clases . '" title="Evidencia de aprendizaje sobre el tema" target="_blank"') ?>
                        <?php endforeach ?>
                    </div>
                    
                    <!--ARCHIVOS-->
                    <a href="#listado_archivos" data-toggle="collapse" aria-expanded="true" class="btn btn-default btn-block" style="margin-bottom: 5px;">
                        <img src="<?php echo URL_IMG ?>flipbook/archivos_banner_v4.png" title="Archivos complementarios">
                    </a>

                    <div id="listado_archivos" class="collapse sep2">
                        <?php foreach ($archivos->result() as $row_archivo) : ?>
                            
                            <?php
                                $clase_pagina = 'pagina_' . $row_archivo->num_pagina;
                            ?>

                            <?php if ( $row_archivo->tipo_archivo == 'audios' ){ ?>
                                <?php $datos_archivo['row_archivo'] = $row_archivo ?>
                                <?php $this->load->view('flipbooks/leer/audio_v', $datos_archivo); ?>

                            <?php } elseif ( $row_archivo->tipo_archivo == 'animaciones' ) { ?>

                                <?php $src_link_animaciones = URL_IMG . 'flipbook/' . $row_archivo->icono; ?>
                                <?php $clases = 'btn btn-default recurso hidden ' . $clase_pagina; ?>    
                                <?= anchor("flipbooks/animacion/{$row_archivo->archivo_id}", img($src_link_animaciones), 'class="' . $clases . '" target="_blank"'); ?>

                            <?php } else { ?>

                                <a href="<?= $carpeta_uploads . $row_archivo->ubicacion ?>" class="btn btn-default recurso hidden <?= $clase_pagina ?>" target="_blank">
                                    <img src="<?= URL_IMG . 'flipbook/' . $row_archivo->icono ?>" />
                                </a>

                            <?php } ?>
                        <?php endforeach ?>
                    </div>
                    
                    <!--ENLACES-->
                    <a href="#listado_links" data-toggle="collapse" aria-expanded="true" class="btn btn-default btn-block" style="margin-bottom: 5px;">
                        <img src="<?= URL_IMG ?>flipbook/link_banner_v4.png">
                    </a>

                    <div id="listado_links" class="collapse sep2">
                        <?php foreach ($links->result() as $row_link) : ?>
                            <?php
                                $clase_pagina = 'pagina_' . $row_link->num_pagina;
                            ?>
                            <a href="<?= $row_link->url ?>" class="btn btn-default recurso <?= $clase_pagina ?>" target="_blank" title="Link complementario sobre este tema">
                                <img src="<?= URL_IMG ?>flipbook/link.png"/>
                            </a>
                        <?php endforeach ?>
                    </div>
                    
                </div>
            </div>

            <!-- IMAGEN PÁGINA E ÍNDICE -->
            <div class="col-md-7 seccion_pagina">
                <div class="text-center">
                    <?= img($att_img) ?>
                </div>
                
                <div id="indice_flipbook">   
                    <div id="titulo_indice">
                        <h3 class="text-center"><?= $titulo_pagina ?></h3>
                    </div>
                    
                    <div id="elementos_indice">
                        <div class="row">
                            <?php foreach ($paginas->result() as $row_pagina) : ?>
                                <?php
                                    $en_indice = TRUE;
                                    if ( is_null($row_pagina->tema_id) ) { $en_indice = FALSE; }
                                    if ( $row_pagina->tema_id == $tema_id_ant ) { $en_indice = FALSE; }
                                    
                                    //Para siguiente página
                                    $tema_id_ant = $row_pagina->tema_id;
                                ?>
                                <?php if ( $en_indice ){ ?>
                                    <div class="col-md-6">
                                        <div class="link_indice pull-left" id="indice_<?= $row_pagina->num_pagina ?>">
                                            <span class="etiqueta nivel w1"><?= $row_pagina->num_pagina  + 1?></span>
                                            <span class="a5_no"><?= $row_pagina->nombre_tema ?></span>
                                        </div>    
                                    </div>
                                <?php } ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ANOTACIÓN-->
            <div class="col-md-3">
                <div class="sep2">
                    <?= form_textarea($att_anotacion) ?>
                </div>
                <div class="sep2">
                    <span id="guardar_anotacion" class="btn btn-warning btn-block">
                        <i class="fa fa-save"></i>
                        Guardar
                    </span>
                    <span id="guardada" class="btn btn-success btn-block">
                        <i class="fa fa-check"></i>
                        Guardarda
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>