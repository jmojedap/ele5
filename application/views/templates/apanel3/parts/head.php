<title><?php echo $titulo_pagina ?></title>
        <link rel="shortcut icon" href="<?= URL_IMG ?>admin/icono.png" type="image/ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        
        <!-- Bootstrap-->
        <?php $this->load->view('head_includes/bootstrap4') ?>

        <script type="text/javascript" src="<?php echo URL_RECURSOS ?>plantillas/apanel3/actions.js"></script>
        
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Ubuntu:500,300'>
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        <link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>plantillas/apanel3/style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>plantillas/apanel3/style_add.css">

        <!-- Vue.js -->
        <?php $this->load->view('assets/vue') ?>

        <?php //$this->load->view('head_includes/google_analytics'); ?>