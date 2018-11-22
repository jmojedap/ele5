<!--Tipo 1, Vídeo de la Plataforma -->
<?php if ( $row->referente_3_id == 1 ) { ?>
    <div class="row">
        <div class="col col-md-12">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe width="100%" style="min-height: 470px" src="<?= $row->texto_1 ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Tipo 2, Imagen -->
<?php if ( $row->referente_3_id == 2 ) { ?>
    <?php if ( ! is_null($row_archivo) ) { ?>
        <div class="row">
            <div class="col col-md-12">
                <img src="<?php echo URL_UPLOADS . $row_archivo->carpeta . $row_archivo->nombre_archivo ?>" style="width: 100%">
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            <i class="fa fa-info-circle"></i>
            No se ha cargado archivo
        </div>
    <?php } ?>
    
<?php } ?>

<!--Tipo 3, Vídeo de YouTube-->
<?php if ( $row->referente_3_id == 3 ) { ?>
    <div class="row">
        <div class="col col-md-12">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe width="100%" style="min-height: 470px" src="<?= $row->texto_1 ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Tipo 4, Archivo PDF -->
<?php if ( $row->referente_3_id == 4 ) { ?>
    <?php if ( ! is_null($row_archivo) ) { ?>
        <div class="row">
            <div class="col col-md-12">
                <iframe src="<?php echo URL_UPLOADS . $row_archivo->carpeta . $row_archivo->nombre_archivo ?>" width="100%" style="border: none; min-height: 800px">
                </iframe>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning">
            <i class="fa fa-info-circle"></i>
            No se ha cargado archivo
        </div>
    <?php } ?>
<?php } ?>

<!-- Tipo 5, Archivo Otros Tipos -->
<?php if ( $row->referente_3_id == 5) { ?>
    <?php if ( ! is_null($row_archivo)  ) { ?>
        <div class="row">
            <div class="col col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3>
                            <?php echo $row_archivo->titulo_archivo ?>
                        </h3>
                        <?= anchor(URL_UPLOADS . $row_archivo->carpeta . $row_archivo->nombre_archivo, '<i class="fa fa-download"></i> Descargar', 'class="btn btn-lg btn-default" title="" download="' . $row_archivo->titulo_archivo . $row_archivo->ext . '"') ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>

<!-- Tipo 6, Link externo -->
<?php if ( $row->referente_3_id == 6) { ?>
        <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $row->nombre_post ?>
        </div>
        <div class="panel-body">
            <p>
                <?php echo $row->resumen ?>
            </p>
            
            <a href="<?php echo $row->texto_1 ?>" class="btn btn-success" target="_blank">
                Abrir
                <i class="fa fa-external-link"></i>
            </a>
        </div>
    </div>
<?php } ?>