<?php

    $password_actual = array(
        'name'  =>  'password_actual',
        'id'    =>  'password_actual',
        'class' =>  'form-control',
        'required'  => TRUE,
        'autofocus'  => TRUE,
        'title'  => 'Escriba su contraseña actual',
        'placeholder' =>   'Escriba su contraseña actual'
    );
    
    $password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'form-control',
        'required'  => TRUE,
        'placeholder' =>   'Escriba su nueva contraseña',
        'pattern' => '.{8,}',   //Al menos 8 caracteres
        'title' => 'Debe tener al menos 8 caractéres'
    );
    
    $re_password = array(
        'name'  =>  're_password',
        'id'    =>  're_password',
        'class' =>  'form-control',
        'required'  => TRUE,
        'placeholder' =>   'Confirme la nueva contraseña',
        'pattern' => '.{8,}',
        'title' => 'Confirme la nueva contraseña'
    );
    
    $submit = array(
        'value' =>  'Guardar',
        'class' =>  'btn btn-primary'
    )

?>
        
<div class="panel panel-default">
    <div class="panel-heading">
        Cambio de contraseña
    </div>
    <div class="panel-body">
        <?= form_open($destino_form); ?>
            <?= form_hidden('id', $usuario_id_cambio); ?>
            <div class="sep2">
                <?= form_password($password_actual); ?>    
            </div>

            <div class="sep2">
                <?= form_password($password); ?>
            </div>

            <div class="sep2">
                <?= form_password($re_password); ?>
            </div>

            <div class="sep2">
                <?= form_submit($submit) ?>        
            </div>
        <?= form_close();?>
    </div>
</div>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>