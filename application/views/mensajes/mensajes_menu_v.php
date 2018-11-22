<?php
    $class = array(
        'recibidos' => '',
        'enviados' => '',
        'nuevo_mensaje' => '',
    );
    
    if ( $this->uri->segment(2) == 'recibidos' ) $class['recibidos'] = 'current';
    if ( $this->uri->segment(2) == 'enviados' ) $class['enviados'] = 'current';
    if ( $this->uri->segment(2) == 'nuevo_mensaje' ) $class['nuevo_mensaje'] = 'current';
?>

<p>
    <?= anchor('mensajes/recibidos', 'Recibidos', 'class="' . $class['recibidos'] . '"') ?>
    <?= anchor('mensajes/enviados', 'Enviados', 'class="' . $class['enviados'] . '"') ?>
    <?= anchor('mensajes/nuevo_mensaje/add', 'Nuevo', 'class="' . $class['nuevo_mensaje'] . '"') ?>
</p>
