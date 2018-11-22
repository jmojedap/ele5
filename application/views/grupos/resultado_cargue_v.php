<?php $this->load->view('grupos/submenu_estudiantes_v') ?>

<?php if ( $cargado ){ ?>
    <h4 class="alert_success">Se cargaron <?= count($usuarios_insertados) ?> estudiantes</h4>
<?php } else { ?>
    <h4 class="alert_error"><?= $mensaje ?></h4>
    <div class="div1">
        <?= anchor("grupos/cargar_estudiantes/{$row->id}", "Volver", 'class="a2" title="Volver"') ?>
    </div>
<?php } ?>
    
<?php if ( $cargado ){ ?>
    <div class="seccion group">
        <div class="col col_box span_2_of_2">
            <div class="info_container_body">
                <h3>Estudiantes cargados desde la hoja: '<?= $nombre_hoja ?>'</h3>
                <?= anchor("grupos/estudiantes/{$row->id}", 'Ir al grupo', 'class="a2"') ?>
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
                            <th>Documento</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios_insertados as $usuario_id) { ?>
                            <?php $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id) ?>
                            <tr>
                                <td><?= anchor("usuarios/actividad/{$row_usuario->id}/1", $row_usuario->username, 'class="" title=""') ?></td>
                                <td><?= $row_usuario->apellidos ?></td>
                                <td><?= $row_usuario->nombre ?></td>
                                <td><?php echo $this->Item_model->nombre(59, $row_usuario->sexo) ?></td>
                                <td><?= $row_usuario->no_documento ?></td>
                                <td><?= $row_usuario->email ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

