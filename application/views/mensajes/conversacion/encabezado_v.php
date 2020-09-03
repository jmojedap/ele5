<?php
    //Clase 
        $clase_q_usuarios = 'form-control';
        if ( $mensajes->num_rows() > 0 ) { $clase_q_usuarios .= ' d-none'; }
?>

<div class="mb-2">
    <span class="text-muted">Iniciada</span>
    <span class="resaltar"><?= $this->pml->date_format($row->creado, 'M-d') ?></span>
    <span class="text-muted"> &middot; </span>

    <span class="text-muted">Hace</span>
    <span class="resaltar"><?= $this->pml->ago($row->creado) ?></span>
    <span class="text-muted"> &middot; </span>

    <span class="resaltar"><?= $cant_mensajes ?></span>
    <span class="text-muted">mensajes</span>
    <span class="text-muted"> &middot; </span>
</div>

<div class="mb-2">
    <a href="<?= base_url("mensajes/nuevo") ?>" class="btn btn-info" title="Crear una nueva conversación">
        <i class="fa fa-plus"></i> Nuevo
    </a>

    <div class="btn btn-primary" id="mostrar_usuarios" title="Mostrar usuarios en la conversación"><i class="fa fa-users"></i> Mostrar (<?= $cant_destinatarios ?>)</div>
    <div class="btn btn-secondary" id="ocultar_usuarios" title="Mostrar usuarios en la conversación"><i class="fa fa-users"></i> Ocultar (<?= $cant_destinatarios ?>)</div>

    <?php if ( $this->session->userdata('user_id') == $row->usuario_id ){ ?>
        <button class="btn btn-secondary" title="Agregar usuario a la conversación" id="mostrar_q_usuarios">
            <i class="fa fa-plus"></i>
            Usuario
        </button>
    <?php } ?>

    <button class="btn btn-warning" title="Eliminar la conversación" data-toggle="modal" data-target="#modal_eliminar">
        <i class="fa fa-trash"></i>
    </button>

</div>

<?php if ( $row->usuario_id == $this->session->userdata('user_id') ){ ?>
    <div class="mb-2">
        <input
            name="q_usuarios" type="text" class="<?= $clase_q_usuarios ?>" id="q_usuarios" required title="Para:" placeholder="Para:"
        >
    </div>
<?php } ?>

<div class="mb-2">
    <p id="lista_usuarios">
        <?php if ( $row->tipo_id == 1 ){ ?>
            <?php foreach ($usuarios->result() as $row_usuario) : ?>
                <?php
                    $link_quitar = TRUE;
                    $mostrar_usuario = TRUE;
                    if ( $row_usuario->usuario_id == $this->session->userdata('usuario_id') )
                    {
                        $mostrar_usuario = FALSE;
                    }
                    if ( $mensajes->num_rows() > 0 ) { $link_quitar = FALSE; }
                ?>

                <?php if ( $mostrar_usuario ) { ?>
                    <span class="removible" id="usuario_<?= $row_usuario->usuario_id ?>">
                        <?= $row_usuario->apellidos . ' ' . $row_usuario->nombre ?>
                        <?php if ( $link_quitar ) : ?>                
                            <i class="fa fa-times link_menor quitar_usuario" title="Quitar al usuario de la conversación" data-usuario_id="<?= $row_usuario->usuario_id ?>"></i>
                        <?php endif ?>
                    </span>
                <?php } ?>

            <?php endforeach ?>
        <?php } elseif ( $row->tipo_id == 2 ) { ?>
            <span class="removible">
                Estudiantes grupo :: <?= $this->App_model->nombre_grupo($row->referente_id); ?>
            </span>
        <?php } elseif ( $row->tipo_id == 3 ) { ?>
            <span class="removible">
                Profesores :: <?= $this->App_model->nombre_institucion($row->referente_id); ?>
            </span>
        <?php } elseif ( $row->tipo_id == 4 ) { ?>
            <span class="removible">
                Estudiantes :: <?= $this->App_model->nombre_institucion($row->referente_id); ?>
            </span>
        <?php } elseif ( $row->tipo_id == 5 ) { ?>
            <span class="removible">
                Usuarios :: <?= $this->App_model->nombre_institucion($row->referente_id); ?>
            </span>
        <?php } ?>
    </p>
</div>