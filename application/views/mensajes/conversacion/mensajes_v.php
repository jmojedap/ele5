<style>
    .url_mensaje{
        margin-top: 1.5em;
    }

    .url_mensaje a{
        font-weight: bold;
        padding: 2px;
        padding-left: 1.5em;
        display: block;
        color: #2196f3;
        border-radius: 3px;
    }

    .url_mensaje a:hover{
        background-color: #e3f2fd;
    }
</style>

<?php if ( $mensajes->num_rows() > 0 ){ ?>
    <div id="mensajes">
        <?php if ( $cant_no_mostrados > 0 ) { ?>
            <?= anchor("mensajes/conversacion_total/{$row->id}", 'Ver conversación completa (' . $cant_mensajes . ' mensajes)', 'class="btn btn-success" title=""') ?>
        <?php } ?>
        <?php foreach ($mensajes->result() as $row_mensaje) : ?>
            <?php
                $clase_mensaje = 'mensaje';
                if ( $row_mensaje->remitente_id == $this->session->userdata('usuario_id') ) { $clase_mensaje = 'mensaje mensaje_propio pull-right'; }
            ?>
            <div class="<?= $clase_mensaje ?>">
                <span class="resaltar"><?= $this->App_model->nombre_usuario($row_mensaje->remitente_id, 2) ?></span>
                <div class="f_derecha" style="display: block">
                    <?= anchor("mensajes/eliminar_mensaje/{$row->id}/{$row_mensaje->mensaje_id}", '&nbsp;<span aria-hidden="true">&times;</span>', 'class="close" title="Eliminar mensaje" style="margin-left: 10px;"') ?>
                    <span class="suave"><?= $this->Pcrn->fecha_formato($row_mensaje->enviado, 'M d') ?> | Hace <?= $this->Pcrn->tiempo_hace($row_mensaje->enviado) ?></span>
                </div>
                <br/>
                <?= $row_mensaje->texto_mensaje ?>
                <?php if ( strlen($row_mensaje->url) > 0 ){ ?>
                    <?php
                        $url = $this->Pcrn->preparar_url($row_mensaje->url);
                        $texto_url = $this->Pcrn->texto_url($row_mensaje->url);
                    ?>
                    <p class="url_mensaje">
                        <a href="<?php echo $url ?>" target="_blank" title="Abrir en otra pestaña"><i class="fa fa-caret-right"></i> <?= $texto_url ?></a>
                    </p>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
        <?php endforeach ?>
    </div>
<?php } ?>