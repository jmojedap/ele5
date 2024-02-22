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
    <div class="container">
        <div id="nav_2">
            <?php if ( isset($nav_2) ) $this->load->view($nav_2); ?>
        </div>

        <div id="nav_3">
            <?php if ( isset($nav_3) ) $this->load->view($nav_3); ?>
        </div>

        <div id="view_a">
            <?php $this->load->view($view_a); ?>
        </div>

        <div id="view_b">
            <?php if ( isset($view_b) ) $this->load->view($view_b); ?>
        </div>
    </div>

    <?php $this->load->view('templates/easypml/main/script') ?>
</body>

</html>