<?php
    $ancho_total = 150 + 10;

    $clases_porcentaje =  $this->App_model->arrays_app('clases_porcentaje');
    $colores_porcentaje =  $this->App_model->arrays_app('colores_porcentaje');
    
    //Variables para clasificación en rangos
        $clases_rango = $this->App_model->arrays_app('clases_rango');
        $texto_rango =  $this->App_model->arrays_app('texto_rango');
        
    //Clase áreas
        $clase_areas = 'btn-default';
        if ( is_null($area_id) ) { $clase_areas = 'btn-primary'; }
?>

<?php $this->load->view('grupos/cuestionarios/submenu_v'); ?>

<div class="sep1">
    <div class="btn-group" role="group" aria-label="...">
        <?= anchor("grupos/cuestionarios/{$row->id}", 'Todas', 'class="w3 btn ' . $clase_areas . '"') ?>
        <?php foreach ($areas->result() as $row_area) : ?>
            <?php
                $clase = 'btn-default';
                if ( $area_id == $row_area->id ) { $clase = 'btn-primary'; }
            ?>
            <?= anchor("grupos/cuestionarios/{$row->id}/{$row_area->id}", $row_area->item_corto, 'class="w3 btn ' . $clase . '" title=""') ?>
        <?php endforeach ?>
    </div>
</div>

<div class="sep1">
    <table class="table bg-blanco">
        <thead>
            <th width="50px">Detalle</th>
            <th>Cuestionario</th>
            
            <th width="80px">Preguntas</th>
            <th width="80px"><i class="fa fa-check"></i></th>
            <th width="80px"><i class="fa fa-times"></i></th>
            <th width="<?= $ancho_total ?>px">%</th>
            <th width="80px" title="Usuarios asignados al cuestionario"><i class="fa fa-users"></i></th>
            <th>Respondieron</th>
            
        </thead>
        <tbody>
            <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
                <?php
                    $resultados_grupo = $this->Cuestionario_model->resultados_grupo($row_cuestionario->cuestionario_id, $row->id);
                
                    //Asignados
                        $filtros_asignados['grupo_id'] = $row->id;
                        $filtros_asignados['cuestionario_id'] = $row_cuestionario->cuestionario_id;
                        $asignados = $this->Cuestionario_model->asignados($filtros_asignados);
                        
                    //Estudiante que respondieron
                        $filtros_respondidos['grupo_id'] = $row->id;
                        $filtros_respondidos['cuestionario_id'] = $row_cuestionario->cuestionario_id;
                        $filtros_respondidos['respondido'] = 1;
                        $respondidos = $this->Cuestionario_model->asignados($filtros_respondidos);
                        
                    //Total preguntas
                        $num_preguntas = $this->Cuestionario_model->num_preguntas($row_cuestionario->cuestionario_id);
                        
                    //Preguntas correctas
                        $correctas = '';
                        $incorrectas = '';
                        $porcentaje = '';
                        if ( $respondidos > 0 ) {
                            $porcentaje = $this->Pcrn->int_percent($resultados_grupo['cant_correctas'], $resultados_grupo['cant_respondidas']);
                            $correctas = $num_preguntas * $porcentaje / 100;
                            $incorrectas = $num_preguntas - $correctas;
                        }
                        
                    //Rangos
                        $rango = $this->App_model->rango_cuestionarios($porcentaje);

                        $clase_rango = '';
                        if ( $rango > 0 ){
                            $clase_rango = $clases_rango[$rango];
                        }
                        
                        $clase_barra = $this->Pcrn->valor_rango($clases_porcentaje, $porcentaje);
                ?>
                <tr>
                    <td><?= anchor("cuestionarios/grupos/{$row_cuestionario->cuestionario_id}/{$row->institucion_id}/{$row->id}", 'Ver', 'class="btn btn-primary" target="_blank"') ?></td>
                    <td>
                        <?= $this->App_model->nombre_cuestionario($row_cuestionario->cuestionario_id) ?>
                    </td>
                    
                    <td><?= $num_preguntas ?></td>
                    <td><?= number_format($correctas, 1) ?></td>
                    <td><?= number_format($incorrectas, 1) ?></td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-<?= $clase_barra ?>" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                                
                                <?= $porcentaje ?>%
                            </div>
                        </div>
                    </td>
                    <td><?= $asignados ?></td>
                    <td><?= $respondidos ?></td>
                    
                </tr>

            <?php endforeach ?>
        </tbody>
    </table>
</div>