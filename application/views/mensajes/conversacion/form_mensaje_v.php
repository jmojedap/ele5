<?php
    //Texto botón
        $texto_boton = 'Enviar';
        if ( $mensajes->num_rows() > 0 ) { $texto_boton = 'Responder'; }

    $seccion = $this->uri->segment(2);
        
    //Formulario mensajes
        $att_form = array(
            'class' => ''
        );
    
        
        
        $att_asunto = array(
            'id' => 'asunto',
            'name' => 'asunto',
            'placeholder' => 'Asunto',
            'required' =>   'required',
            'title' =>   'Escriba el asunto del mensaje',
            'class' =>  'form-control'
        );
        
        $att_texto_mensaje = array(
            'id' => 'texto_mensaje',
            'name' => 'texto_mensaje',
            'class' => 'form-control',
            'placeholder' => 'Escriba un mensaje...',
            'rows' => 3,
            'autofocus' => TRUE,
            'title' =>   'Escriba aquí el mensaje',
            'required' =>   'required'
        );
        
        $att_url = array(
            'id' => 'url',
            'class' =>  'form-control',
            'name' => 'url',
            'placeholder' => 'Dirección web'
        );
        
        $att_enviar = array(
            'id' => 'boton_enviar',
            'class' =>  'btn btn-primary',
            'value' =>  $texto_boton
        );
?>

<?= form_open("mensajes/enviar/{$row->id}", $att_form) ?>
    <?= form_hidden('conversacion_id', $row->id) ?>
    <?php if ( is_null($row->asunto) ){ ?>
        <div class="sep2" style="width: 98%">
            <?= form_input($att_asunto) ?>
        </div>
    <?php } ?>


    <div class="sep2" style="width: 98%;">
        <?= form_textarea($att_texto_mensaje) ?>
    </div>

    <div class="sep2" style="width: 98%;" id="casilla_url">
        <?= form_input($att_url) ?>
    </div>

    <div class="sep2">
        <?= form_submit($att_enviar) ?>

        <span class="btn btn-default small" id="mostrar_url">
            <i class="fa fa-link"></i> Agregar link
        </span>

        <?php if ( $cant_mensajes == 0 ){ ?>
            <?= anchor("mensajes/eliminar/{$row->id}", '<i class="fa fa-times"></i> Descartar', 'class="btn btn-warning" title="Decartar nuevo mensaje"') ?>
        <?php } ?>
    </div>
<?= form_close() ?>