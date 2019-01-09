<head>
    <title><?= $titulo_pagina ?></title>
    <link rel="shortcut icon" href="<?= URL_IMG ?>admin/icono.png" type="image/ico" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <!-- Bootstrap-->
    <?= $this->load->view('head_includes/bootstrap') ?>

    <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Ubuntu:500,300'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo URL_RECURSOS ?>css/style_add.css">
    <link rel="stylesheet" href="<?php echo URL_RECURSOS ?>css/flipbook_v4.css">
    
    <!--Bootstrap Submenú-->
    <link rel="stylesheet" href="<?= URL_ASSETS ?>bootstrap_submenu/dist/css/bootstrap-submenu.min.css">
    <script src="<?= URL_ASSETS ?>bootstrap_submenu/dist/js/bootstrap-submenu.min.js" defer></script>
    <script>
        $('[data-submenu]').submenupicker();
    </script>

    <!--Slider-->
    <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
    <script src="<?php echo URL_RECURSOS ?>js/jquery.ui.touch-punch.min.js"></script>

    <script type="text/javascript" src="<?php echo URL_RECURSOS ?>js/pcrn.js"></script> <!--Funciones especiales-->

    <?php
        //Seguimiento google analytics
        $this->load->view('head_includes/google_analytics');
    ?>

    <?php $this->load->view('flipbooks/leer_v3/leer_js_v') ?>

</head>