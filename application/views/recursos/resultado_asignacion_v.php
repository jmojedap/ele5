<?php if ( $cargado ){ ?>
    <h4 class="alert_success">Se asignaron <?= count($archivos_asignados) ?> archivos</h4>
<?php } else { ?>
    <h4 class="alert_error"><?= $mensaje ?></h4>
    <div class="div1">
        <?= anchor("recursos/asignar/{$row->id}", "Volver", 'class="a2" title="Volver"') ?>
    </div>
<?php } ?>
    
<?php if ( $cargado ){ ?>
    <div class="seccion group">
        <div class="col col_box span_2_of_2">
            <div class="info_container_body">
                <h3>Archivos asignados desde la hoja: '<?= $nombre_hoja ?>'</h3>
                <p>
                    <?= $mensaje ?>
                </p>

                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nombre archivo</th>
                            <th>Tema</th>
                            <th>Tipo archivo</th>
                            <th>Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recursos->result() as $row_archivo) { ?>
                            <?php //$row_archivo = $this->Pcrn->registro_id('recurso', $archivo_id) ?>
                            <tr>
                                <td><?= anchor("archivos/actividad/{$row_archivo->id}/1", $row_archivo->nombre_archivo, 'class="" title=""') ?></td>
                                <td><?= $row_archivo->nombre_tema ?></td>
                                <td><?= $row_archivo->tipo_archivo ?></td>
                                <td><?= $row_archivo->disponible ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

