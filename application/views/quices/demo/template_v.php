<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
        
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('templates/apanel3/parts/head'); ?>
    </head>
    <body>
        <?php $this->load->view($view_a) ?>
    </body>
</html>