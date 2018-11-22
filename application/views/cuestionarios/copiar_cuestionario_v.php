<?php

    $att_nombre_cuestionario = array(
        'name' => 'nombre_cuestionario',
        'class' => 'form-control',
        'value' => "{$row->nombre_cuestionario} - Copia"
    );
        
    $att_descripcion = array(
        'name' => 'descripcion',
        'class' => 'form-control',
        'rows' => 5,
        'value' => "Copia de {$row->nombre_cuestionario} | {$row->descripcion}"
    );
        
    $att_submit = array(
        'class' =>  'btn btn-primary',
        'value' =>  'Crear'
    );
    

?>

<?= form_open('cuestionarios/generar_copia') ?>
    <?= form_hidden('cuestionario_id', $row->id) ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="div1">
                <label for="nombre_cuestionario" class="label1">Nombre del cuestionario</label><br/>
                <p class="descripcion">Nombre del nuevo cuestionario</p>
                <?= form_input($att_nombre_cuestionario) ?>
            </div>
            <div class="div1">
                <label for="descripcion" class="label1">Descripción</label><br/>
                <p class="descripcion">Descripción del cuestionario nuevo</p>
                <?= form_textarea($att_descripcion) ?>
            </div>
            <div class="div1">
                <?= form_submit($att_submit) ?>
            </div>
        </div>
    </div>
        

<?= form_close() ?>

<?php if ( validation_errors() ):?>
    <div class="modulo2 width_full">
        <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
    </div>
<?php endif ?>