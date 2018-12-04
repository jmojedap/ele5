<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
        
    //Sidebar, según el rol del usuario
        $carpeta_plantilla = 'plantillas/pikeadmin/';
?>

<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view($carpeta_plantilla . 'partes/head_v'); ?>
        <script>
            const app_url = '<?php echo base_url() ?>';
            var app_cf = '<?php echo $this->uri->segment(1) . '/' . $this->uri->segment(2); ?>';
        </script>
    </head>

    <body class="adminbody">
        <div id="main">

            <!--Barra superior-->
            <?php $this->load->view($carpeta_plantilla . 'partes/navbar_v'); ?>

            <!--Barra lateral izquierda-->
            <?php $this->load->view($carpeta_plantilla . 'menus/sidebar_v'); ?>
            
            <!--Contenido-->
            <div class="content-page" id="contenido">

                
                <div class="content">
                    <div class="container-fluid">
                        <!--Encabezado-->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="breadcrumb-holder" style="min-height: 47px">
                                    <h1 class="main-title_no float-left" id="titulo_pagina">
                                        <?php echo $titulo_pagina ?>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        
                        <div id="menu_a">
                            <?php if ( ! is_null($menu_a) ) { ?>
                                <?php $this->load->view($menu_a); ?>
                            <?php } ?>
                        </div>
                        
                        <div id="vista_a">
                            <?php $this->load->view($vista_a); ?>
                        </div>
                    </div>
                </div>
            </div>
            

            <footer class="footer">
                <span class="text-right">
                    Copyright <a target="_blank" href="#"><?php echo NOMBRE_APP ?></a>
                </span>
                <span class="float-right">
                    Creado por <a target="_blank" href="http://www.pacarina.com"><b>Pacarina Media Lab</b></a>
                </span>
            </footer>

        </div>

        <?php $this->load->view($carpeta_plantilla . 'partes/foot_scripts_v'); ?>
        <?php $this->load->view($carpeta_plantilla . 'partes/docready_v'); ?>
    </body>
</html>