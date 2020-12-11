<?php

    //Textos
        $textos['subtitulo'] = 'Activación de cuenta';
        $textos['boton'] = 'Activar mi cuenta';
        
        if ( $tipo_activacion == 'restaurar' ) {
            $textos['subtitulo'] = 'Restauración de contraseña';
            $textos['boton'] = 'Enviar';
        }
?>

<div class="center_box_450 text-center">
    <h2 class="resaltar"><?= $row->nombre . ' ' . $row->apellidos ?></h2>
    <h3 class="suave"><?= $textos['subtitulo'] ?></h3>
    <p class="suave"><?= $row->username ?></p>
    <p>Establezca su contraseña para la Plataforma Enlace</p>

    <?= form_open($destino_form); ?>
        <div class="form-group">
            <input
                name="password" type="password" class="form-control form-control-lg"
                required autofocus pattern=".{8,}"
                title="Debe tener al menos 8 caracteres" placeholder="Contraseña"
            >
        </div>
        <div class="form-group">
            <input
                name="passconf" type="password" class="form-control form-control-lg"
                required minlength="8"
                title="Confirme su contraseña" placeholder="Confirme su contraseña"
            >
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-lg btn-block" type="submit">
                <?= $textos['boton'] ?>
            </button>
        </div>
    <?= form_close(); ?>

    <div class="mt-2">
        <?= validation_errors('<div class="alert alert-danger" role="alert">', '</div>') ?>
    </div>
</div>

