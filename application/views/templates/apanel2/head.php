<title><?php echo $titulo_pagina ?></title>
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>admin/icono.png" type="image/ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo URL_RESOURCES ?>templates/apanel2/actions.js"></script>
        
        <!-- Bootstrap-->
        <?php $this->load->view('head_includes/bootstrap_online') ?>
        
        <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Ubuntu:500,300'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/apanel2/style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo URL_RESOURCES ?>templates/apanel2/style_add.css">

        <?php //$this->load->view('head_includes/google_analytics'); ?>
        
        <?php

            /**
             * Inclusión de scripts adicionales, desde el controlador, var $data['head_includes]
             * 
             * Si se requiere cargar algún código adicional en el head se cargan los segmentos de código del head 
             * de la página definidos en el array $head_includes, esta variable se define en la función del controlador
             * como $data['head_includes']
             */

              if ( isset($head_includes) ):
                  foreach ($head_includes as $value):
                      $this->load->view("head_includes/{$value}");
                  endforeach;
              endif;

        ?>