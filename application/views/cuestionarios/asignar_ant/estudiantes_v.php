<?php
    $anio = 0;
    
    //Variables para clasificación en rangos
        $texto_rango =  $this->App_model->arrays_app('texto_rango');
        $clases_porcentaje =  $this->App_model->arrays_app('clases_porcentaje');
        
        $roles_responder = array(0,1,2,7);
        $roles_editar = array(0,1,2,3,4,5);
    
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro',
            'checked' => FALSE
        );
        
        $seleccionados_todos = '';
        foreach ( $estudiantes->result() as $row_resultado ) {
            $seleccionados_todos .= '-' . $row_resultado->uc_id;
        }
?>

<table class="table bg-blanco" cellspacing="0"> 
    <thead>
    <th width="10px;" class="hidden"><?= form_checkbox($att_check_todos) ?></th>
    <th>Estudiante</th>
    <th class="hidden">Lapso</th>
    <th class="centrado" width="100px">
        Estado
    </th>

    <th class="centrado w5">% Correctas</th>
    <th width="95px"></th>

</thead>
<tbody>
    <?php foreach ($estudiantes->result() as $row_estudiante) : ?>
        <?php
            //Variables
            $nombre_estudiante = $row_estudiante->nombre . ' ' . $row_estudiante->apellidos;
            $link_estudiante = anchor("usuarios/resultados/{$row_estudiante->usuario_id}/{$row_estudiante->uc_id}", $nombre_estudiante);

            $link_responder = anchor("cuestionarios/resolver_lote/$row_estudiante->uc_id", '<i class="fa fa-pencil-square-o"></i>', 'class="btn btn-default btn-xs"');
            $link_reiniciar = $this->Pcrn->anchor_confirm("cuestionarios/reiniciar/{$row_estudiante->uc_id}/1", '<i class="fa fa-repeat"></i>', 'class="btn btn-warning btn-xs" title="Reiniciar el cuestionario para este estudiante"', "Las respuestas de este estudiante para esta prueba se eliminarán ¿Desea continuar?");
            $link_finalizar = $this->Pcrn->anchor_confirm("cuestionarios/finalizar_externo/{$row_estudiante->uc_id}/grupo", '<i class="fa fa-check"></i>', 'class="btn btn-info btn-xs" title="Finalizar el cuestionario de este estudiante"', "Se calcularán totales y se finalizará el cuestionario de este estudiante ¿Desea continuar?");
            $porcentaje_con_respuesta = number_format(100 * $row_estudiante->num_con_respuesta / $this->Pcrn->no_cero($row->num_preguntas), 0);

            $filtros['usuario_pregunta.usuario_id'] = $row_estudiante->usuario_id;
            $filtros['usuario_pregunta.cuestionario_id'] = $row->id;
            $cant_correctas = $this->Cuestionario_model->cant_correctas_simple($filtros);

            $resultado = $this->App_model->res_cuestionario($row->id, "usuario_id = {$row_estudiante->usuario_id}");
            $porcentaje_correctas = $this->Pcrn->int_percent($cant_correctas, $row->num_preguntas);

            $rango_usuario = $this->App_model->rango_cuestionarios($porcentaje_correctas / 100);

            $clase_fecha = 'correcto';
            if ($row_estudiante->fecha_fin < date('Y-m-d H:i:s')) {
                $prefijo_hace = ' | Vencido hace ';
                $clase_fecha = 'rojo';
            }

            $clase_estado = '';
            if ($row_estudiante->estado >= 3) {
                $clase_estado = 'info';
            }

            $clase_barra = $this->Pcrn->valor_rango($clases_porcentaje, $porcentaje_correctas);

            //Checkbox
            $att_check['data-id'] = $row_estudiante->uc_id;
        ?>

        <tr>
            <td class="hidden">
                <?= form_checkbox($att_check) ?>
            </td>

            <td>
                <?php echo $link_estudiante ?>
            </td>

            <td class="hidden">
                <span class=""><?= $this->Pcrn->fecha_formato($row_estudiante->fecha_inicio, 'd-M') ?></span>
                <span class="suave">a</span>
                <?php echo $this->Pcrn->fecha_formato($row_estudiante->fecha_fin, 'd-M') ?>
            </td>

            <td class="text-center <?= $clase_estado ?>">
                <?= $this->Item_model->nombre(151, $row_estudiante->estado); ?>
            </td>

            <td class="centrado">
                <?php if ($row_estudiante->estado >= 3) { ?>
                    <div class="progress">
                        <div class="progress-bar progress-bar-<?= $clase_barra ?>" role="progressbar" aria-valuenow="<?= $porcentaje_correctas ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje_correctas ?>%;">
                            <span class="sr-only"><?= $porcentaje_correctas ?>%</span>
                            <?= $porcentaje_correctas ?>%
                        </div>
                    </div>
                <?php } ?>
            </td>

            <td>
                <?php if (in_array($this->session->userdata('rol_id'), $roles_responder)) { ?>
                    <?php echo $link_responder ?>
                <?php } ?>        

                <?php if (in_array($this->session->userdata('rol_id'), $roles_editar)) { ?>
                    <?php if ($row_estudiante->estado > 1) { ?>
                        <?php //echo $link_reiniciar ?>
                        <button class="btn btn-warning btn_reiniciar_uc" data-uc_id="<?php echo $row_estudiante->uc_id ?>">
                            <i class="fa fa-repeat"></i>
                        </button>
                    <?php } ?>

                    <?php if ($row_estudiante->estado == 2) { ?>
                        <?php echo $link_finalizar ?>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>