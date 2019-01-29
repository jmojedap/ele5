<?php
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

    //Clases columnas
        $clases_col['nivel'] = 'hidden-xs';
        $clases_col['tipo'] = 'hidden-xs';
        $clases_col['creado_por'] = 'hidden-xs';
        $clases_col['editado'] = 'hidden-xs';
        $clases_col['anio_generacion'] = 'hidden-xs';
        
    //Arrays con valores para contenido en lista
        $arr_tipos = $this->Item_model->arr_interno('categoria_id = 161');
        $arr_nivel = $this->Item_model->arr_interno('categoria_id = 3');

?>

<table class="table table-default bg-white">
    <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="50px;">ID</th>
                <th></th>
                
                <th class="<?= $clases_col['usuario'] ?>">Asignación</th>
                
                <th class="<?= $clases_col['nivel'] ?>" style="min-width: 200px;">Nivel Área</th>
                <th class="<?= $clases_col['resumen'] ?>">Resultado</th>
                <th class="<?= $clases_col['estado'] ?>">Estado</th>
                <th class="<?= $clases_col['fechas'] ?>"></th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = 'Ver más';
                    $link_elemento = anchor("usuarios/resultados/{$row_resultado->usuario_id}/{$row_resultado->id}", $nombre_elemento, 'class="btn btn-default" target="_blank"');
                    $editable = $this->Cuestionario_model->editable($row_resultado->id);
                    
                //Referencia
                    $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $row_resultado->cuestionario_id);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;
                    
                //Otras
                    $cant_preguntas = $this->Cuestionario_model->num_preguntas($row_cuestionario->id);
                    $resumen = json_decode($row_resultado->resumen);
                    
                    $pct = $this->Pcrn->int_percent($resumen->total[0], $resumen->total[1]);
                    $clase_pct = $this->App_model->bs_clase_pct($pct);
            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    
                    <td class="warning"><?= $row_resultado->id ?></td>
                    
                    <td>
                        <?= $link_elemento ?>
                    </td>
                    
                    <td>
                        <?= anchor("cuestionarios/grupos/{$row_resultado->cuestionario_id}/{$row_resultado->institucion_id}/{$row_resultado->grupo_id}", $row_cuestionario->nombre_cuestionario) ?>
                        <i class="fa fa-caret-right"></i>
                        <br/>
                        <?= anchor("usuarios/cuestionarios/{$row_resultado->usuario_id}", $this->App_model->nombre_usuario($row_resultado->usuario_id, 2)) ?>
                    </td>
                    
                    <td class="<?= $clases_col['nivel'] ?>">
                        <span class="etiqueta nivel w1"><?= $row_cuestionario->nivel ?></span>
                        <?= $this->App_model->etiqueta_area($row_cuestionario->area_id) ?>
                    </td>
                    <td class="<?= $clases_col['resumen'] ?>">
                        <?= $this->App_model->bs_progress_bar($pct, $pct . '%', $clase_pct); ?>
                    </td>
                    <td class="<?= $clases_col['estado'] ?>">
                        <?= $this->Item_model->nombre(151, $row_resultado->estado) ?>
                    </td>
                    <td class="<?= $clases_col['editado'] ?>">
                        <span class="suave">editado: </span>
                        <?= $this->Pcrn->tiempo_hace($row_resultado->editado, 'Y-M-d'); ?>
                        <br/>
                        <span class="suave">creado: </span>
                        <?= $this->Pcrn->tiempo_hace($row_resultado->creado, 'Y-M-d'); ?>
                    </td>
                </tr>

            <?php } //foreach ?>
    </tbody>
</table>  

<?= $this->load->view('app/modal_eliminar'); ?>