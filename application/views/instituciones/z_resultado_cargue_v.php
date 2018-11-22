<?php $this->load->view('instituciones/submenu_usuarios_v') ?>

<?php //Número de usuarios cargados ?>

<?php if ( $cargado ){ ?>
    <h4 class="alert_success">Se cargaron <?= count($usuarios_insertados) ?> usuarios</h4>
<?php } else { ?>
    <h4 class="alert_error"><?= $mensaje ?></h4>
    <div class="div1">
        <?= anchor("instituciones/cargar_usuarios/{$row->id}", "Volver", 'class="a2" title="Volver"') ?>
    </div>
<?php } ?>
    
<?php if ( $cargado ){ ?>
    <div class="seccion group">
        <div class="col col_box span_2_of_2">
            <div class="info_container_body">
                <h3>Estudiantes cargados desde la hoja: '<?= $nombre_hoja ?>'</h3>
                <?= anchor("instituciones/usuarios/{$row->id}", 'Ir al institución', 'class="a2"') ?>
                <p>
                    <?= $mensaje ?>
                </p>

                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Apellidos</th>
                            <th>Nombre</th>
                            <th>Sexo</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios_insertados as $usuario_id) { ?>
                            <?php $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id) ?>
                            <tr>
                                <td><?= anchor("usuarios/actividad/{$row_usuario->id}/1", $row_usuario->username, 'class="" title=""') ?></td>
                                <td><?= $row_usuario->apellidos ?></td>
                                <td><?= $row_usuario->nombre ?></td>
                                <td><?php //echo $this->Item_model->nombre($row_usuario->sexo, 'sexo') ?></td>
                                <td><?php //echo $this->Item_model->nombre($row_usuario->rol_id, 'user_level') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

