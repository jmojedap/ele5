    <title><?= $titulo_pagina ?></title>
    <link rel="shortcut icon" href="<?php echo URL_IMG ?>admin/icono.png" type="image/ico" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Ubuntu:500,300'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>css/apanel/style.css">
    <link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>css/apanel/style_add.css">
    <link type="text/css" rel="stylesheet" href="<?php echo URL_RECURSOS ?>css/quiz.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo URL_RECURSOS ?>js/Math.uuid.js"></script>
    <script type="text/javascript" src="<?php echo URL_RECURSOS ?>js/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo URL_RECURSOS ?>js/apanel/actions_2.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
    <script src="<?php echo URL_RECURSOS ?>js/jquery.ui.touch-punch.min.js"></script>

    <script type="text/javascript" src="<?php echo URL_RECURSOS ?>js/pcrn.js"></script> <!--Funciones especiales-->

    <?php
        //Seguimiento google analytics
        $this->load->view('head_includes/google_analytics');
    ?>