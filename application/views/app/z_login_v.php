<?php

    //$row_admin = $this->Pcrn->registro_id('usuario', 1);

    $valor_username = '';
    if ( $this->session->flashdata('username') ) { $valor_username = $this->session->flashdata('username'); }

    $username = array(
        'name'  =>  'username',
        'id'    =>  'username',
        'value' =>  $valor_username,
        'class' =>  'form-control login',
        'required' => 'required',
        'autofocus' => TRUE,
        'title' => 'Escriba su nombre de usuario',
        'placeholder' =>   'usuario'
    );
    
    
    
    $password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'form-control login',
        'required' => 'required',
        'title' => 'Escriba su contraseña',
        'placeholder' =>   'contrase&ntilde;a'
    );
    
    $submit = array(
        'value' =>  'Ingresar',
        'class' => 'btn btn-success btn-block'
    );
?>

<div class="login">

    <?= form_open('app/validar_login'); ?>

        <div class="form-group">
            <?= form_input($username); ?>    
        </div>

        <div class="form-group">
            <?= form_password($password); ?>
        </div>

        <div class="">
            <?= form_submit($submit) ?>    
        </div>

    <?= form_close(); ?>

    <div class="clearfix"></div>

    <div class="sep2">
        <?php if ( $this->session->flashdata('mensajes') ):?>
            <?php $mensajes = $this->session->flashdata('mensajes'); ?>
            <?php foreach ($mensajes as $mensaje) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $mensaje ?>
                </div>
            <?php endforeach ?>

        <?php endif ?>
    </div>
</div>


    
