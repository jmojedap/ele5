<?php

    $mensaje = NULL;

    if ( $resultado == 'no_encontrado' ) {
        $clase = 'alert-warning';
        $mensaje = '<i class="fa fa-info-circle"></i> No existe ningún usuario con el correo electrónico enviado.';
    }
    
    if ( $resultado == 'enviado' ) {
        $clase = 'alert-success';
        $mensaje = '<i class="fa fa-check-circle"></i> Enviamos un mensaje a su correo electrónico para restaurar su contraseña. El mensaje puede tardar varios minutos en llegar. Revise también la carpeta de correo no deseado.';
    }
    
    //Elementos formulario
    $att_email = array(
        'id'     => 'campo-email',
        'name'   => 'email',
        'required'   => TRUE,
        'autofocus'   => TRUE,
        'class'  => 'form-control',
        'pattern'  => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$',
        'placeholder'   => 'Correo electrónico',
        'title'   => 'Escriba su dirección de correo electrónico'
    );
    
    $submit = array(
        'value' =>  'Enviar',
        'class' => 'btn btn-primary btn-block'
    );
    
?>

<div class="login">
    <p class="text-center">
        Ingrese su dirección de <span class="resaltar">correo electrónico</span>, enviaremos un mensaje para restaurar la contraseña de su cuenta de usuario.
    </p>

    <?= form_open($destino_form); ?>

        <div class="form-group">
            <?= form_input($att_email) ?>
        </div>

        <div class="">
            <?= form_submit($submit) ?>    
        </div>

    <?= form_close(); ?>

    <div class="clearfix"></div>

    <div class="sep2">
        <?php if ( ! is_null($mensaje) ):?>
            <div class="alert <?= $clase ?>" role="alert">
                <?= $mensaje ?>
            </div>
        <?php endif ?>
    </div>
</div>