<?php

    $att_form = array(
        'class' => 'form-horizontal'
    );

    $att_nombre_tema = array(
        'name' => 'nombre_tema',
        'class' => 'form-control',
        'value' => "{$row->nombre_tema} - Copia",
        'required' => 'required'
    );
        
    $att_cod_tema = array(
        'name' => 'cod_tema',
        'class' => 'form-control',
        'value' => $row->cod_tema,
        'required' => 'required'
    );
        
    $att_descripcion = array(
        'name' => 'descripcion',
        'class' => 'form-control',
        'value' => "Copia de {$row->nombre_tema} | {$row->descripcion}",
        'rows' =>   3
    );
        
    $att_submit = array(
        'class' =>  'btn btn-primary w3',
        'value' =>  'Crear'
    );
    

?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= form_open($destino_form, $att_form) ?>
            <?= form_hidden('tema_id', $row->id) ?>
            <div class="form-group">
                <label for="cod_tema" class="col-md-3 control-label">Código tema</label>
                <div class="col-md-9">
                    <?= form_input($att_cod_tema) ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="nombre_tema" class="col-md-3 control-label">Nombre del tema</label>
                <div class="col-md-9">
                    <?= form_input($att_nombre_tema) ?>
                </div>
            </div>
        
            <div class="form-group">
                <label for="descripcion" class="col-md-3 control-label">Descripción</label>
                <div class="col-md-9">
                    <?= form_textarea($att_descripcion) ?>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        <?= form_close('') ?>
    </div>
</div>

<?php if ( validation_errors() ):?>
    <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
<?php endif ?>