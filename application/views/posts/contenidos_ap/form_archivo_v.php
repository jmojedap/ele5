<?php //$this->load->view('head_includes/chosen_jquery'); ?>

<?php

    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    //$opciones_item = $this->Item_model->opciones('categoria_id = 2', 'Seleccione el item');

    $att_archivo = array(
        'name' => 'archivo'
    );
    
    $att_submit = array(
        'class' => 'btn btn-primary',
        'value' => 'Cargar'
    );
?>

<?php if ( strlen($row->imagen_id) > 0 ) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Archivo complementario
        </div>
        <div class="panel-body">
            <?= form_open_multipart($destino_form_archivo, $att_form) ?>
                <div class="form-group">
                    <label for="archivo" class="col-sm-3 control-label">Archivo *</label>
                    <div class="col-sm-9">
                        <?= form_upload($att_archivo) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?= form_submit($att_submit) ?>
                    </div>
                </div>
            <?= form_close('') ?>
            <hr/>
            El tamaño máximo de archivo es 7MB
        </div>
    </div>
<?php } else { ?>
    <?php
        //$link_archivo = RUTA_UPLOADS . $row_archivo->carpeta . $row_archivo->nombre_archivo;
    ?>
<?php } ?>

