<?php

    $att_form = array(
        'class' => 'form-horizontal'
    );

    $att_nombre_programa = array(
        'name' => 'nombre_programa',
        'class' => 'form-control',
        'required' => TRUE,
        'value' => "{$row->nombre_programa} - Copia"
    );
        
    $att_descripcion = array(
        'name' => 'descripcion',
        'class' => 'form-control',
        'rows' => 5,
        'value' => "Copia del programa {$row->nombre_programa} | {$row->descripcion}"
    );
        
    $att_submit = array(
        'class' =>  'btn btn-primary w3',
        'value' =>  'Crear'
    );
    

?>

<?= form_open($destino_form, $att_form) ?>
    <?= form_hidden('programa_id', $row->id) ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label for="nombre_programa" class="col-sm-3 control-label">Nombre del programa *</label>
                <div class="col-sm-9">
                    <?= form_input($att_nombre_programa) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                <div class="col-sm-9">
                    <?= form_textarea($att_descripcion) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        </div>
    </div>

<?= form_close() ?>

<?php $this->load->view('comunes/validation_errors_v'); ?>