<?php
    $condiciones[0] = "grupo_id = {$grupo_id}";
    $clases_porcentaje =  $this->App_model->arrays_app('clases_porcentaje');
    $colores_porcentaje =  $this->App_model->arrays_app('colores_porcentaje');
    
    //Clase áreas
        $clase_areas = 'btn-default';
        if ( is_null($area_id) ) { $clase_areas = 'btn-primary'; }
?>

<?php $this->load->view('grupos/cuestionarios/submenu_v'); ?>

<div class="sep1">
    <div class="btn-group" role="group" aria-label="...">
        <?php foreach ($areas->result() as $row_area) : ?>
            <?php
                $clase = 'btn-default';
                if ( $area_id == $row_area->id ) { $clase = 'btn-primary'; }
            ?>
            <?= anchor("grupos/cuestionarios_resumen01/{$grupo_id}/{$row_area->id}", $row_area->item_corto, 'class="w3 btn ' . $clase . '" title=""') ?>
        <?php endforeach ?>
    </div>
</div>

<table class="table table-default bg-blanco">
    <thead>
        <th class="<?= $clases_col['nombre_cuestionario'] ?>">
            Cuestionario
        </th>
        <?php foreach($competencias->result() as $row_competencia) : ?>
            <th width="25%">
                <?= $row_competencia->nombre_competencia ?>
            </th>
        <?php endforeach ?>
    </thead>
    
    <tbody>
        <?php foreach($cuestionarios->result() as $row_cuestionario) : ?>
            <?php
                $row_ctn_plus = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);

                foreach( $arr_competencias as $no_competencia => $competencia_id )
                {
                    $condicion = "grupo_id = {$grupo_id} AND area_id = {$area_id} AND competencia_id = {$competencia_id}";
                    $resultados[$no_competencia] = $this->Cuestionario_model->resultado($row_cuestionario->cuestionario_id, $condicion);
                }
            ?>
            <tr>
                <td class="<?= $clases_col['nombre_cuestionario'] ?>">
                    <?= anchor("cuestionarios/grupos/{$row_cuestionario->cuestionario_id}/{$row->institucion_id}/{$grupo_id}", $this->App_model->nombre_cuestionario($row_cuestionario->cuestionario_id)) ?>
                    <br/>
                    <span class="resaltar"><?= $row_ctn_plus->num_preguntas ?></span>
                    <span class="suave">preguntas</span>
                    
                </td>
                <?php foreach( $arr_competencias as $no_competencia => $competencia_id ) : ?>
                    <?php
                        $porcentaje = $resultados[$no_competencia]['porcentaje'];
                        $clase_barra = $this->Pcrn->valor_rango($clases_porcentaje, $porcentaje);                        
                    ?>
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-<?= $clase_barra ?>" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
                                <?= $porcentaje ?>%
                            </div>
                        </div>
                    </td>
                <?php endforeach ?>
            </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>

