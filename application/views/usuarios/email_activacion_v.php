<?php
//Contenido
    $textos['titulo'] = 'Bienvenido a la Plataforma En Línea Editores';
    $textos['parrafo'] = 'Para activar su cuenta haga clic en el siguiente link';
    $textos['boton'] = 'Activar mi cuenta';
    $textos['link'] = "usuarios/activar/{$row_usuario->cod_activacion}";
    
    if ( $tipo_activacion == 'restaurar' ) {
        $textos['titulo'] = 'Plataforma En Línea Editores';
        $textos['parrafo'] = 'Para restaurar su contraseña haga clic en el siguiente link';
        $textos['boton'] = 'Restaurar mi contraseña';
        $textos['link'] = "usuarios/activar/{$row_usuario->cod_activacion}/restaurar";
    }

//Estilos
    $styles['body'] = 'font-family: Helvetica, Sans-Serif; text-align: center; padding-top: 1em;';
    $styles['h1'] = 'color: #89CB4E';
    $styles['h3'] = 'color: #666';
    $styles['a'] = 'color: #00b0f0';
    $styles['footer'] = 'color: #666; background-color: #FAFAFA; margin-top: 2em; height: 2em; padding-top: 1em; font-size: 0.8em;';

?>
<div style="<?= $styles['body'] ?>">
    <h1 style="<?= $styles['h1'] ?>"><?= $textos['titulo'] ?></h1>
    <h3 style="<?= $styles['h3'] ?>"><?= $row_usuario->nombre . ' ' . $row_usuario->apellidos ?></h3>
    <p><?= $textos['parrafo'] ?></p>
    <a href="<?= base_url($textos['link']) ?>" style="<?= $styles['a'] ?>">
        <?= $textos['boton'] ?>
    </a>
    <div style="<?= $styles['footer'] ?>">
        &copy; En Línea Editores &middot; Colombia
    </div>
</div>

