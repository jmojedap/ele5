<style>
    .explore_head{
        border-bottom: 1px solid #f9f9f9;
        padding: 5px 0px;
        margin: 1px;
        background-color: #e3f2fd;
    }

    .explore_row{
        border-bottom: 1px solid #e9e9e9;
        padding: 5px 0px;
        margin: 1px;
    }

    .explore_row:hover{
        background-color: #f9f9f9;
        border-bottom: 1px solid #f1f1f1;
    }

    .explore_col_id { width: 80px; }
    .explore_col_check {
        max-width: 300px;
        background-color: red;
    }
</style>

<div class="card" style="padding: 0px;">
    <div>
        <div class="row explore_head">
            <div class="col-md-1 col-xs-6" style="max-width: 30px;">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="check_todos" name="check_todos">
                    <label class="custom-control-label" for="check_todos">
                        <span class="text-hide">-</span>
                    </label>
                </div>
            </div>
            <div class="col">
                ID
            </div>
        </div>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
        <?php
            //Variables
                $nombre_elemento = character_limiter($row_resultado->apellidos. ' ' . $row_resultado->nombre, 70);
                $link_elemento = anchor("{$controlador}/actividad/{$row_resultado->id}", $nombre_elemento);

            //Botón activo/inactivo
                $activacion['clase'] = 'btn-success';
                $activacion['contenido'] = 'Activo';
                if ( $row_resultado->estado == 0)
                {
                    $activacion['clase'] = 'btn-warning';
                    $activacion['contenido'] = 'Inactivo';
                }

            //Botón pago
                $pago['clase'] = 'btn-success';
                $pago['contenido'] = 'Pagado';
                if ( $row_resultado->pago == 0)
                {
                    $pago['clase'] = 'btn-warning';
                    $pago['contenido'] = 'Sin pago';
                }
        ?>
            <div class="row explore_row" id="fila_<?php echo $row_resultado->id ?>">

                <div class="col-md-1 col-xs-6" style="max-width: 30px;">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input check_registro" data-id="<?php echo $row_resultado->id ?>" id="check_<?php echo $row_resultado->id ?>">
                        <label class="custom-control-label" for="check_<?php echo $row_resultado->id ?>">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </div>
                
                <div class="col-md-1 col-xs-6" style="max-width: 80px;">
                    <?php echo $row_resultado->id ?>
                </div>
                
                <div class="col-md-4 col-sm-12">
                    <div class="media">
                        <a href="<?php echo base_url("usuarios/actividad/{$row_resultado->id}") ?>">
                            <img src="<?php echo URL_IMG . "users/sm_user_{$row_resultado->rol_id}.png" ?>" alt="Rol usuario" class="mr-3 rounded-circle" width="30px">
                        </a>
                        <div class="media-body">
                            <?php echo $link_elemento ?>
                            <br>
                            <span class="text-muted"><?php echo $this->Item_model->nombre(58, $row_resultado->rol_id) ?></span>
                            &middot;
                            <span class="text-muted"><?php echo $row_resultado->username ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-12">
                    <span class="text-muted">
                        <i class="fa fa-university"></i>
                    </span>
                    <?= $this->App_model->nombre_institucion($row_resultado->institucion_id) ?>
                </div>

                <div class="col-md-3 col-xs-12">
                    <?php if ( $this->session->userdata('role') <= 2 ) : ?>
                        <button
                            id="alternar_<?php echo $row_resultado->id ?>"
                            class="btn btn-sm alternar_activacion <?php echo $activacion['clase'] ?>"
                            data-usuario_id="<?= $row_resultado->id ?>"
                            title="Activar/Desactivar"
                            style="width: 70px;"
                            >
                            <?php echo $activacion['contenido'] ?>
                        </button>
                    <?php endif; ?>

                    <button
                        id="pago_<?php echo $row_resultado->id ?>"
                        class="btn btn-sm alternar_pago <?php echo $pago['clase'] ?>"
                        data-usuario_id="<?= $row_resultado->id ?>"
                        title="Pagado Sí/No"
                        style="width: 70px;"
                        >
                        <?php echo $pago['contenido'] ?>
                    </button>

                    <button 
                        id="restaurar_<?php echo $row_resultado->id ?>"
                        class="btn btn-light btn-sm restaurar_contrasena"
                        data-usuario_id="<?= $row_resultado->id ?>"
                        title="Restaurar contraseña del usuario"
                        style="width: 90px;"
                        >
                        <i class="fa fa-sync-alt"></i>
                        Contraseña
                    </button>

                    <?php if ( $this->session->userdata('rol_id') <= 1 ) { ?>
                        <a href="<?php echo base_url("develop/ml/{$row_resultado->id}") ?>" class="btn btn-sm btn-light" title="Ingresar como este usuario">
                            <i class="fa fa-sign-in-alt"></i>
                        </a>
                    <?php } ?>
                </div>
            </div>

        <?php } //foreach ?>

    </div>
</div>
