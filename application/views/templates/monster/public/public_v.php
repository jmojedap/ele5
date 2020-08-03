<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/monster/parts/head_v') ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" integrity="sha256-PHcOkPmOshsMBC+vtJdVr5Mwb7r0LkSVJPlPrp/IMpU=" crossorigin="anonymous" />
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/monster/public.css">

        <script>
            const app_url = '<?php echo base_url() ?>';
        </script>
    </head>

    <body class="fix-header card-no-border fix-sidebar">
        <div id="main-wrapper">
            <?php $this->load->view('templates/monster/public/header_v') ?>
            <?php $this->load->view('templates/monster/public/content_v') ?>
        </div>
        
        <?php $this->load->view('templates/monster/parts/footer_scripts_v') ?>
    </body>
</html>
