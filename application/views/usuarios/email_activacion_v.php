<?php
    $textos['titulo'] = 'Bienvenido a la Plataforma Enlace - En Línea Editores';
    $textos['parrafo'] = 'Para activar su cuenta haga clic en el siguiente link';
    $textos['boton'] = 'Activar mi cuenta';
    $textos['link'] = "usuarios/activar/{$row_usuario->cod_activacion}";
    
    if ( $tipo_activacion == 'restaurar' ) {
        $textos['titulo'] = 'Plataforma Enlace - En Línea Editores';
        $textos['parrafo'] = 'Para restaurar su contraseña haga clic en el siguiente link';
        $textos['boton'] = 'Restaurar mi contraseña';
        $textos['link'] = "usuarios/activar/{$row_usuario->cod_activacion}/restaurar";
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Activación de Cuenta</title>
        
        <!--JQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        
        <!--Bootstrap-->
        <?php $this->load->view('head_includes/bootstrap') ?>

        <!-- Style adicionales PCRN-->
        <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Ubuntu:500,300'>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 style="color: #00b0f0"><?= $textos['titulo'] ?></h1>
                    <h3 class="suave"><?= $row_usuario->nombre . ' ' . $row_usuario->apellidos ?></h3>
                    <p><?= $textos['parrafo'] ?></p>
                    <?= anchor($textos['link'], $textos['boton'], 'class="btn btn-success"') ?>
                </div>
            </div>
        </div>
        
        
    </body>
    
</html>

