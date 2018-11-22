<?php

    $username = array(
        'name'  =>  'username',
        'id'    =>  'username',
        'value' =>  $row->username,
        'class' =>  'i-texto1'
    );
    
    $email = array(
        'name'  =>  'email',
        'id'    =>  'email',
        'value' =>  $row->email,
        'class' =>  'i-texto1'
    );
    
    $nombre = array(
        'name'  =>  'nombre',
        'id'    =>  'nombre',
        'value' =>  $row->nombre,
        'class' =>  'i-texto1'
    );
    
    $apellidos = array(
        'name'  =>  'apellidos',
        'id'    =>  'apellidos',
        'value' =>  $row->apellidos,
        'class' =>  'i-texto1'
    );
    
    $sexo = 'class="select1"';
    
    $sexo_opciones = array(
        0   =>  'Seleccione una opción',
        1   =>  'Mujer',
        2   =>  'Hombre'
    );
    
    $submit = array(
        'value' =>  'Actualizar',
        'class' => 'boton1'
    )

?>
    
    <div class="div1">
        <div class="div-gris">
            <span class="titulo-1">Editando a <?= $row->username ?></span>
            <?= form_open("usuarios/actualizar/{$row->id}"); ?>
                <div class="div2">
                    <label for="username" class="label1">Nombre usuario</label><br/>
                    <?= form_input($username); ?>
                </div>
                <div class="div1">
                    <label for="email" class="label1">E-mail</label><br/>
                    <?= form_input($email); ?>
                </div>
            
                <div class="div1">
                    <label for="nombre" class="label1">Nombre</label><br/>
                    <?= form_input($nombre); ?>
                </div>
            
                <div class="div1">
                    <label for="apellidos" class="label1">Apellidos</label><br/>
                    <?= form_input($apellidos); ?>
                </div>
            
                <div class="div1">
                    <label for="sexo" class="label1">Sexo</label><br/>
                    <?= form_dropdown("sexo", $sexo_opciones, $row->sexo, $sexo); ?>
                </div>
                    <?= form_submit($submit) ?>
            <?= form_close();?>
            
        </div>
        
    </div>
    
    <div class="">
        <div class="div1">
            <?= anchor('usuarios/lista', 'Volver') ?>
        </div>
        
        
        <div class="rojo">
            <?= validation_errors() ?>
        </div>
        
    </div>