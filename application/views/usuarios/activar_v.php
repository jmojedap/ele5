<?php

    //Textos
        $textos['subtitulo'] = 'Activación de cuenta';
        $textos['boton'] = 'Activar mi cuenta';
        
        if ( $tipo_activacion == 'restaurar' ) {
            $textos['subtitulo'] = 'Restauración de contraseña';
            $textos['boton'] = 'Enviar';
        }

    $password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'form-control',
        'required' => 'required',
        'placeholder' =>   'contrase&ntilde;a',
        'autofocus' =>  TRUE,
        'pattern' => '.{8,}',
        'title' => 'Debe tener al menos 8 caractéres'
    );
    
    $passconf = array(
        'name'  =>  'passconf',
        'id'    =>  'passconf',
        'class' =>  'form-control',
        'required' => 'required',
        'placeholder' =>   'confirme su contrase&ntilde;a',
        'minlength' => 8
    );
    
    $submit = array(
        'id' => 'submit_form',
        'value' =>  $textos['boton'],
        'class' => 'btn btn-success btn-block'
        
    );
    
?>

<div class="row">
    
    <div class="col-md-4 col-md-offset-4">
        
        <div class="" style="text-align: center; padding-top: 60px; padding-bottom: 40px;">
            <h2 class="resaltar"><?= $row->nombre . ' ' . $row->apellidos ?></h2>
            <h3 class="suave"><?= $textos['subtitulo'] ?></h3>
            <p class="suave"><?= $row->username ?></p>
            <p>Establezca su contraseña para la Plataforma Enlace</p>
        </div>
            
        <div>
            <?= form_open($destino_form); ?>
                <div class="form-group">
                    <?= form_password($password); ?>
                </div>
                <div class="form-group">
                    <?= form_password($passconf); ?>    
                </div>
                <div class="">
                    <?= form_submit($submit) ?>    
                </div>
            <?= form_close(); ?>
        </div>
        
        <div class="sep2">
            <?= validation_errors('<div class="alert alert-danger" role="alert">', '</div>') ?>
        </div>
        
    </div>
</div>