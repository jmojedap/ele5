<?php
    

    $att_form = array(
        'class' =>  'form-inline'
    );
    
    $att_submit = array(
        'value' => 'Cargar',
        'class' =>  'btn btn-primary sep2'
    );
    
    $att_archivo = array(
        'name' => 'archivo'
    );
?>

<h3>Imagen elemento</h3>

<?= form_open_multipart("quices/cargar_imagen_elemento/{$row->id}", $att_form) ?>
    <?= form_hidden('elemento_id', $elemento_id)  ?>
    <?= form_upload($att_archivo) ?>
    <?= form_submit($att_submit) ?>
<?= form_close('') ?>