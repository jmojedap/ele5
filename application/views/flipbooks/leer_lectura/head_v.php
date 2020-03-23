<head>
    <title><?php echo $head_title ?></title>
    <link rel="shortcut icon" href="<?php echo URL_IMG ?>admin/icono.png" type="image/ico" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <!-- Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <?php $this->load->view('head_includes/bootstrap4') ?>

    <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Ubuntu:500,300'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/style_add.css">
    
    
    <!--Bootstrap Submenú-->
    <link rel="stylesheet" href="<?= URL_ASSETS ?>bootstrap_submenu/dist/css/bootstrap-submenu.min.css">
    <script src="<?= URL_ASSETS ?>bootstrap_submenu/dist/js/bootstrap-submenu.min.js" defer></script>

    <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/flipbook_bs4.css">

    <script type="text/javascript" src="<?php echo URL_RESOURCES ?>js/pcrn.js"></script> <!--Funciones especiales-->
    
    <?php $this->load->view('assets/vue'); ?>
    <?php $this->load->view('assets/toastr'); ?>

    <?php
        //Seguimiento google analytics
        $this->load->view('head_includes/google_analytics');
    ?>
</head>