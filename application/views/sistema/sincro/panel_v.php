<?php $this->load->view('sistema/sincro/panel_js'); ?>

<?php $this->load->view($vista_menu); ?>

<?php 
    $ot = $this->input->get('ot');
    $ot_alt = $this->Pcrn->alternar($ot, 'ASC', 'DESC');
    
    $nombre_metodo = 'Auto';
    if ( $metodo_id > 0 ) { $nombre_metodo = $this->Item_model->nombre(71, $metodo_id); }
?>

<div class="sep1">
    <button id="act_estado_servidor" class="btn btn-primary" title="Actualizar estado de tablas del servidor">
        <i class="fa fa-refresh"></i>
        Estado servidor
    </button>
    
    <!-- Split button -->
    <div class="btn-group">
        <button type="button" class="btn btn-default w3"><?= $nombre_metodo ?></button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a>Método sincro</a>
            </li>
            <li role="separator" class="divider"></li>
            <li>
                <?= anchor("sincro/panel", 'Auto') ?>
            </li>
            <li>
                <?= anchor("sincro/panel/1", 'Total') ?>
            </li>
            <li>
                <?= anchor("sincro/panel/2", 'Nuevos ID') ?>
            </li>
        </ul>
    </div>
</div>

<table class="table table-hover bg-blanco" id="tabla_proceso">
    <thead>
        <th width="40px"></th>
        <th>
            <?= anchor("sincro/panel/?ob=nombre_tabla&ot={$ot_alt}", 'Tabla', 'class="" title=""') ?>
        </th>
        <th class="text-center">
            <i class="fa fa-clock-o"></i>
        </th>
        <th>
            ID L/S
        </th>
        <th>
            <?= anchor("sincro/panel/?ob=cant_registros&ot={$ot_alt}", 'Reg L/S') ?>
        </th>
        <th>Dif</th>
        <th width="100px">Estado</th>
        <th>Sincronizados</th>
        <th>Porcentaje</th>
        <th>
            <?= anchor("sincro/panel/?ob=fecha_sincro&ot={$ot_alt}", 'Hace') ?>
        </th>
    </thead>
    <tbody>
        <?php foreach ($tablas->result() as $row_tabla) : ?>
            <?php
                $clase_hace = '';
                $dias_hace = $this->Pcrn->dias_lapso($row_tabla->fecha_sincro, date('Y-m-d H:i:s'));
                if ( $dias_hace < 3 && ! is_null($dias_hace) ) { $clase_hace = 'info'; }
                if ( $dias_hace > 15 && ! is_null($dias_hace) ) { $clase_hace = 'warning'; }

                $fecha_hace = $this->Pcrn->fecha_formato($row_tabla->fecha_sincro, 'M-d H:i');

                $tiempo_estimado = $this->Pcrn->segundos_lapso($row_tabla->fecha_inicio, $row_tabla->fecha_sincro);
                
                //Diferencia
                $att_dif['valor'] = $row_tabla->cant_registros - $row_tabla->cant_registros_servidor;
                if ( $row_tabla->metodo_id == 2 ) { $att_dif['valor'] = $row_tabla->max_id - $row_tabla->max_ids; }
                
                $att_dif['clase'] = '';
                if ( $att_dif['valor'] < 0 ) { $att_dif['clase'] = 'warning'; }
                if ( $att_dif['valor'] > 0 ) { $att_dif['clase'] = 'info'; }
                
                //Sincronizables
                    $sincronizables = $row_tabla->cant_registros_servidor;
                    if ( $row_tabla->metodo_id == 2 ) { $sincronizables = $row_tabla->cant_registros_servidor - $row_tabla->cant_registros; }
                
                //Barra porcentaje
                    $pct_reg = 0;
                    $clase_barra = '';
                
            ?>

            <tr id="fila_<?= $row_tabla->nombre_tabla ?>">
                <td>
                    <span id="sincro_<?= $row_tabla->nombre_tabla ?>" class="btn btn-warning sincro btn-sm" data-table="<?= $row_tabla->nombre_tabla ?>" data-desde_id="<?= $row_tabla->max_id ?>" data-metodo_id="<?= $row_tabla->metodo_id ?>">
                        <i class="fa fa-refresh"></i>
                    </span>
                </td>
                <td>
                    <?= $row_tabla->nombre_tabla ?>
                    <br/>
                    <span class="suave">
                        <?= $this->Item_model->nombre(71, $row_tabla->metodo_id); ?>
                    </span>
                </td>

                <td class="text-center">
                    <?= $this->Pcrn->lapso($tiempo_estimado) ?>
                </td>
                
                <td class="text-right">
                    <span class="suave">
                        <?= number_format($row_tabla->max_id, 0, ',', '.') ?>
                    </span>
                    <br/>
                    <span class="suave">
                        <?= number_format($row_tabla->max_ids, 0, ',', '.') ?>
                    </span>
                </td>
                
                <td class="text-right">
                    <?= number_format($row_tabla->cant_registros, 0, ',', '.') ?>
                    <br/>
                    <span class="resaltar">
                        <?= number_format($row_tabla->cant_registros_servidor, 0, ',', '.') ?>
                    </span>
                </td>
                
                <td class="<?= $att_dif['clase'] ?> text-right">
                    <?= number_format($att_dif['valor'],0,',','.') ?>
                </td>

                <td id="estado_<?= $row_tabla->nombre_tabla ?>">
                </td>

                <td class="text-right">
                    <span class="text-right" id="cant_sincronizados_<?= $row_tabla->nombre_tabla ?>">
                    </span>
                </td>
                <td id="porcentaje_<?= $row_tabla->nombre_tabla ?>">
                    <div class="progress">
                        <div id="barra_porcentaje_<?= $row_tabla->nombre_tabla ?>" class="progress-bar active <?= $clase_barra ?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: <?= $pct_reg ?>%">
                            
                        </div>
                    </div>
                </td>
                

                <td id="tiempo_hace_<?= $row_tabla->nombre_tabla ?>" class="<?= $clase_hace ?>" title="<?= $fecha_sincro ?>">
                    <?= $this->Pcrn->tiempo_hace($row_tabla->fecha_sincro) ?>
                </td>
            </tr>
        <?php endforeach ?>

    </tbody>
</table>         