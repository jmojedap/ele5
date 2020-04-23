<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
        
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('templates/apanel3/parts/head'); ?>
        <?php $this->load->view('templates/apanel3/parts/routes_script_v') ?>
    </head>
    <body>
        <?php $this->load->view("templates/apanel3/menus/menu_{$this->session->userdata('rol_id')}"); ?>
        <?php $this->load->view('templates/apanel3/parts/head_tools') ?>
        <?php $this->load->view('templates/apanel3/parts/content'); ?>
    </body>
</html>