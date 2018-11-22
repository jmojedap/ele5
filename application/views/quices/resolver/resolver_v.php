<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
        
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('quices/resolver/head_v'); ?>
    </head>
    <body>
        <div id="quiz_contenido" class="quiz_contenido">
            <div class="f_derecha" style="padding: 20px">
                <img width="100px" src="<?php echo URL_IMG ?>admin/logo_enlinea.png" />
            </div>
            
            <h1 class="resaltar">
                <i class="fa fa-caret-right"></i> <?= $row_tema->nombre_tema ?>
            </h1>
            <h2 class="">
                <i class="fa fa-caret-right"></i>
                <?= $row_tipo_quiz->enunciado ?>
            </h2>

            <hr/>

            <div class="div3">
                <?= $row->texto_enunciado ?>
            </div>
            
            <div class="quiz_detalle">
                <?php //echo $vista_a ?>
                <?php $this->load->view($vista_a); ?>
            </div>
            
            <div class="div3">
                <span class="button orange" id="enviar">
                    Enviar
                </span>
            </div>
            
            <h4 id="resultado_correcto" class="alert_success">¡Correcto, felicitaciones!</h4>
            <h4 id="resultado_incorrecto" class="alert_warning">Incorrecto, inténtalo de nuevo</h4>
            
        </div>
        
    </body>
</html>