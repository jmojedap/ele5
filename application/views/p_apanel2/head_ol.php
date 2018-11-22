
<title><?= $titulo_pagina ?></title>
<link rel="shortcut icon" href="<?= base_url() . RUTA_IMG ?>app/icono.png" type="image/ico" />

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="<?= base_url() ?>css/apanel/ubuntu.css">
<link rel="stylesheet" href="<?= base_url() ?>css/apanel/font-awesome.css">
<link rel="stylesheet" href="<?= base_url() ?>css/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/col.css" media="all">
<link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/2cols.css" media="all">
<link rel="stylesheet" href="<?= base_url() ?>css/responsivegridsystem/3cols.css" media="all">
<link rel="stylesheet" href="<?= base_url() ?>css/apanel/style.css">
<link rel="stylesheet" href="<?= base_url() ?>css/apanel/style_add.css">

<script type="text/javascript" language="javascript" src="<?= base_url() ?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/apanel/actions.js"></script>

<!-- Vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

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