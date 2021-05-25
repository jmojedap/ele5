<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('templates/monster/parts/head_v'); ?>
        <?php $this->load->view('assets/sweetalert2') ?>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <link href="<?= URL_RESOURCES . 'css/quiz_v2.css' ?>" rel="stylesheet">
    </head>
    <body>
        <?php $this->load->view('quices/resolver_v2/resolver_v') ?>
    </body>
</html>


    
