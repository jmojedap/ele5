<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
        
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('p_apanel2/head'); ?>
    </head>
    <body>
        <?php $this->load->view("p_apanel2/menus/menu_{$this->session->userdata('rol_id')}"); ?>
        <?php $this->load->view('p_apanel2/main_container'); ?>
    </body>
</html>