<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/monster/parts/head_v') ?>
        <style>
            .tema-articulo {
                width: 750px;
                margin: 0 auto;
                background-color: #FAFAFA;
                padding: 3em;
                font-size: 1.2em;
                box-shadow: 5px 5px 15px 2px rgba(51,51,51,1);
            }

            .tema-articulo h1 {
                color: #1e4f9c;
            }
            .tema-articulo h2 {
                /*color: #e98a0a;*/
                color: #a40d3a
            }

            .tema-articulo img {
                width: 100%;
            }

            .tema-articulo p {
                text-align: justify;
            }

            .tema-articulo p strong {
                color: #a40d3a;
            }
        </style>
    </head>

    <body class="" style="background: #04bdbf;">
        <div class="p-2">
            <div class="tema-articulo">
                <h2><?= $articulo->nombre_post ?></h2>
                <?= $articulo_contenido_html ?>
            </div>
        </div>
    </body>
</html>
