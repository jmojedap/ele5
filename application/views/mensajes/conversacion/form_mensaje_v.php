<form accept-charset="utf-8" method="POST" id="message_form" action="<?= base_url("mensajes/enviar/{$row->id}") ?>">
    <input type="hidden" name="conversacion_id" value="<?= $row->id ?>">
    <?php if ( is_null($row->asunto) ){ ?>
        <div class="mb-2 w98pc">
            <input
                name="asunto" type="text" class="form-control" required
                title="Escriba el asunto de la conversación" placeholder="Escriba el asunto de la conversación..."
            >
        </div>
    <?php } ?>

    <div class="mb-2 w98pc">
        <textarea
            name="texto_mensaje" type="text" class="form-control"
            required autofocus rows="3"
            title="Escriba un mensaje..." placeholder="Escriba un mensaje..."
        ></textarea>
    </div>

    <div class="mb-2 w98pc" id="casilla_url">
        <input
            name="url" type="text" class="form-control"
            title="Dirección Web" placeholder="Dirección Web"
        >
    </div>

    <div class="mb-2">
        <button class="btn btn-primary w120p" type="submit">
            Enviar
        </button>

        <button class="btn btn-secondary" id="mostrar_url" type="button">
            <i class="fa fa-link"></i> Agregar link
        </button>

        <?php if ( $cant_mensajes == 0 ){ ?>
            <?= anchor("mensajes/eliminar/{$row->id}", '<i class="fa fa-times"></i> Descartar', 'class="btn btn-warning" title="Decartar nuevo mensaje"') ?>
        <?php } ?>
    </div>
</form>

