<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('flipbooks/lectura/6_articulos_demo/head_v') ?>
        <link href="<?= URL_RESOURCES ?>css/contenidos_articulos_v01.css" rel="stylesheet">
    </head>
    <body>
        <?php $this->load->view('flipbooks/lectura/6_articulos_demo/body_v') ?>
    </body>
</html>
