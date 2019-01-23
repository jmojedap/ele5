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
        <div id="quiz_contenido" class="quiz_contenido card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h1 class="resaltar">
                            </i> <?php echo $row_tema->nombre_tema ?>
                        </h1>
                        
                        <h4>
                            <i class="fa fa-info-circle"></i>
                            <?php if ( strlen($row->texto_enunciado) > 0 ) { ?>
                                <?php echo $row->texto_enunciado ?>
                            <?php } else { ?>
                                <?php echo $row_tipo_quiz->enunciado ?>
                            <?php } ?>
                        </h4>
                    </div>
                    <div class="col-md-2">    
                        <img class="float-right" width="100px" src="<?php echo URL_IMG ?>admin/logo_enlinea.png" />
                    </div>
                </div>
            
            
                <div class="quiz_detalle">
                    <?php //echo $vista_a ?>
                    <?php $this->load->view($vista_a); ?>
                </div>
                
                <div class="mb-3 text-center">
                    <button class="btn btn-primary btn-lg" id="enviar" style="width: 150px;">
                        Enviar
                    </button>
                </div>

                <div class="mb-3">
                    <p id="resultado_correcto" class="alert alert-success">
                        <i class="fa fa-check"></i>
                        ¡Correcto, felicitaciones!
                    </p>
                    <p id="resultado_incorrecto" class="alert alert-warning">
                        <i class="fa fa-warning"></i>
                        Incorrecto, inténtalo de nuevo
                    </p>
                </div>

            </div>
        </div>
        
    </body>
</html>