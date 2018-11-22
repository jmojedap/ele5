<?php

    //Clase 
        $clase_q_usuarios = 'form-control';
        if ( $mensajes->num_rows() > 0 ) { $clase_q_usuarios .= ' hide'; }

    //Formulario selección de usuarios
        $att_q_usuarios = array(
            'id'     => 'q_usuarios',
            'name'   => 'q_usuarios',
            'class'  => $clase_q_usuarios,
            'placeholder'   => 'Para:'
        );
?>

<div class="sep1">
    <span class="suave">Iniciada</span>
    <span class="resaltar"><?= $this->Pcrn->fecha_formato($row->creado, 'M-d') ?></span>
    <span class="suave"> | </span>

    <span class="suave">Hace</span>
    <span class="resaltar"><?= $this->Pcrn->tiempo_hace($row->creado) ?></span>
    <span class="suave"> | </span>

    <span class="resaltar"><?= $cant_mensajes ?></span>
    <span class="suave">mensajes</span>
    <span class="suave"> | </span>
</div>

<div>
    <?= anchor("mensajes/nuevo", '<i class="fa fa-plus"></i> Nuevo', 'class="btn btn-info" title="Crear una nueva conversación"') ?>

    <div class="btn btn-primary" id="mostrar_usuarios" title="Mostrar usuarios en la conversación"><i class="fa fa-users"></i> Mostrar (<?= $cant_destinatarios ?>)</div>
    <div class="btn btn-default" id="ocultar_usuarios" title="Mostrar usuarios en la conversación"><i class="fa fa-users"></i> Ocultar (<?= $cant_destinatarios ?>)</div>

    <?php if ( $this->session->userdata('usuario_id') == $row->usuario_id ){ ?>
        <button class="btn btn-default" title="Agregar usuario a la conversación" id="mostrar_q_usuarios">
            <i class="fa fa-plus"></i>
            Usuario
        </button>
    <?php } ?>

    <a class="btn btn-warning" title="Eliminar la conversación" data-toggle="modal" data-target="#modal_eliminar">
        <i class="fa fa-trash-o"></i>
    </a>

</div>

<?php if ( $row->usuario_id == $this->session->userdata('usuario_id') ){ ?>
    <div class="sep2">
        <?= form_input($att_q_usuarios) ?>
    </div>
<?php } ?>

<div class="sep2">
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