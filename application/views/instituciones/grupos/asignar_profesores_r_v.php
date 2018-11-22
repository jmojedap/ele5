<?php $this->load->view('instituciones/grupos/submenu_grupos_v') ?>

<?php if ( $cargado ){ ?>
    <div class="alert alert-success" role="alert">Se hicieron <?= $num_cargados ?> asignaciones de profesores</div>
<?php } else { ?>
    <div class="alert alert-danger" role="alert"><?= $mensaje ?></div>
    <div class="div2">
        <?= anchor("instituciones/asignar_profesores/{$row->id}", 'Volver', 'class="btn btn-default" title="Volver"') ?>
    </div>
<?php } ?>
    
<?php if ( $num_no_cargados > 0 ){ ?>
    <div class="alert alert-danger">No se hicieron <?= $num_no_cargados ?> asignaciones</div>
<?php } ?>
    
<?php if ( $cargado ){ ?>
    <h3>Asignaciones realizadas desde la hoja: '<?= $nombre_hoja ?>'</h3>
    <table class="table table-hover bg-blanco" cellspacing="0">
        <thead>
            <tr>
                <th width="90px">Grupo</th>
                <th>Profesor</th>            
                <th>Área</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cargados as $asignacion_id) { ?>
                <?php
                    $row_gp = $this->Pcrn->registro_id('grupo_profesor', $asignacion_id);
                ?>
                
                <tr>
                    <td><?= anchor("grupos/profesores/{$row_gp->grupo_id}", $this->App_model->nombre_grupo($row_gp->grupo_id), 'class="a2"') ?></td>
                    <td><?= $this->App_model->nombre_usuario($row_gp->profesor_id, 2) ?></td>
                    <td><?= $this->App_model->etiqueta_area($row_gp->area_id); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>