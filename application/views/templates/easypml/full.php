<?php
//Evitar errores de definición de variables e índices de arrays, 2013-12-07
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ERROR);
?>
<!doctype html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/easypml/main/head') ?>
    </head>
    <body>
        <?php $this->load->view('templates/easypml/main/navbar') ?>
        <div id="view_a">
            <?php $this->load->view($view_a); ?>
        </div>

        <?php $this->load->view('templates/easypml/main/script') ?>
    </body>
</html>