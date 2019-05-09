<title><?= $titulo_pagina ?></title>
        <link rel="shortcut icon" href="<?= base_url() . RUTA_IMG ?>admin/icono.png" type="image/ico" />

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        
        <!--JQuery-->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        
        <!--Bootstrap-->
        <link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap.min.css">
        <script type="text/javascript" src="<?= base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Ubuntu:500,300'>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        
        <link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/col.css" media="all">
        <link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/2cols.css" media="all">
        <link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/3cols.css" media="all">
        <link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/4cols.css" media="all">

        
        
        <link type="text/css" rel="stylesheet" href="<?= base_url() ?>css/apanel/style.css">
        <link type="text/css" rel="stylesheet" href="<?= base_url() ?>css/apanel/style_add.css">
        <script type="text/javascript" src="<?php echo URL_RESOURCES ?>js/apanel/actions.js"></script>
        
        <!--Google analytics-->
        <?php $this->load->view('head_includes/google_analytics'); ?>

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

          <?php

            /**
             * Inclusión de scripts adicionales, desde el controlador, var $data['head_includes]
             * 
             * Si se requiere cargar algún código adicional en el head se cargan los segmentos de código del head 
             * de la página definidos en el array $head_includes, esta variable se define en la función del controlador
             * como $data['head_includes']
             */
          ?>


        <script type="text/javascript">
            $(document).ready(function() {
            //Inicio de document.ready
                <?php //include 'js/ready_includes/tablesorter.js' ?>
                //Fin funciones auto

                <?php
                    if ( isset($ready_includes) ):
                        foreach ($ready_includes as $value){
                            include "js/ready_includes/" .  $value . ".js";

                        }
                    endif;
                ?>
            //Fin de document.ready
            });
        </script>