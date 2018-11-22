<?php

    $att_nombre_flipbook = array(
        'name' => 'nombre_flipbook',
        'class' => 'i-texto1',
        'value' => "{$row->nombre_flipbook} - Copia"
    );
        
    $att_descripcion = array(
        'name' => 'descripcion',
        'class' => 'textarea1',
        'value' => "Copia de {$row->nombre_flipbook} | {$row->descripcion}"
    );
        
    $att_submit = array(
        'class' =>  'button orange',
        'value' =>  'Crear'
    );
    

?>

<?= form_open('flipbooks/generar_copia') ?>
    <?= form_hidden('flipbook_id', $row->id) ?>

    <div class="seccion group">
        <div class="col col_box span_2_of_2">
            <div class="info_container_body">
                <div class="div1">
                    <label for="nombre_flipbook" class="label1">Nombre del flipbook</label><br/>
                    <p class="descripcion">Nombre del nuevo flipbook</p>
                    <?= form_input($att_nombre_flipbook) ?>
                </div>
                <div class="div1">
                    <label for="descripcion" class="label1">Descripción</label><br/>
                    <p class="descripcion">Descripción del flipbook nuevo</p>
                    <?= form_textarea($att_descripcion) ?>
                </div>
                <div class="div1">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        </div>
    </div>
        

<?= form_close() ?>

<?php if ( validation_errors() ):?>
    <div class="modulo2 width_full">
        <?= validation_errors('<h4 class="alert_error">', '</h4>') ?>
    </div>
<?php endif ?>