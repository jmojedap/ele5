<?php
    $att_form = array(
        'class' =>  'form-horizontal'
    );
    
    $att_submit = array(
        'value' => 'Cargar',
        'class' =>  'btn btn-primary'
    );
    
    $att_archivo = array(
        'name' => 'archivo'
    );
?>

<h4>Imagen de fondo</h4>

<?php if ( $this->session->flashdata('message') ){ ?>
    <?= $this->session->flashdata('message') ?>
<?php } ?>

<?= form_open_multipart("quices/cargar_imagen/{$row->id}", $att_form) ?>
    <?= form_hidden('quiz_id', $row->id)  ?>
    <div class="sep2">
        <?= form_upload($att_archivo) ?>
    </div>

    <div class="sep2">
        <?= form_submit($att_submit) ?>
    </div>
<?= form_close('') ?>