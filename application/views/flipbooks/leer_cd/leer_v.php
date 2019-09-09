<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html>
    <?php $this->load->view('flipbooks/leer_cd/head_v'); ?>
    <?php $this->load->view('flipbooks/leer_cd/body_v'); ?>
</html>