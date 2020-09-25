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
        $clases_col['nivel'] = 'd-none d-sm-table-cell d-lg-table-cell';
        $clases_col['tipo'] = 'd-none d-md-table-cell d-lg-table-cell';
        $clases_col['preguntas'] = 'd-none d-md-table-cell d-lg-table-cell';
        $clases_col['creado'] = 'd-none d-lg-table-cell d-xl-table-cell';
        $clases_col['creado_por'] = 'd-none d-lg-table-cell d-xl-table-cell';
        
    //Arrays con valores para contenido en lista
        $arr_tipos = $this->Item_model->arr_interno('categoria_id = 161');
        $arr_nivel = $this->Item_model->arr_interno('categoria_id = 3');

?>

<table class="table bg-white" cellspacing="0">
    <thead>
            <tr class="">
                <th width="10px">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="check_todos" name="check_todos">
                        <label class="custom-control-label" for="check_todos">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </th>
                <th width="50px;">ID</th>
                <th>Cuestionario</th>
                
                <th class="<?= $clases_col['nivel'] ?>" style="min-width: 200px;">Nivel Área</th>
                <th class="<?= $clases_col['preguntas'] ?>">Preguntas</th>
                <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
                <th class="<?= $clases_col['creado_por'] ?>">Creado por</th>
                <th class="<?= $clases_col['creado'] ?>">Creado</th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre_cuestionario;
                    $link_elemento = anchor("cuestionarios/vista_previa/$row_resultado->id", $nombre_elemento);
                    $editable = $this->Cuestionario_model->editable($row_resultado->id);
                    
                //Otras
                    $cant_preguntas = $this->Cuestionario_model->num_preguntas($row_resultado->id);

            ?>
                <tr id="fila_<?php echo $row_resultado->id ?>">
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input check_registro" data-id="<?php echo $row_resultado->id ?>" id="check_<?php echo $row_resultado->id ?>">
                            <label class="custom-control-label" for="check_<?php echo $row_resultado->id ?>">
                                <span class="text-hide">-</span>
                            </label>
                        </div>
                    </td>
                    
                    <td><?php echo $row_resultado->id ?></td>
                    
                    <td>
                        <?php echo $link_elemento ?>
                    </td>
                    
                    <td class="<?= $clases_col['nivel'] ?>">
                        <span class="etiqueta nivel w1"><?= $row_resultado->nivel ?></span>
                        <?= $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                    </td>
                    <td class="<?= $clases_col['preguntas'] ?>">
                        <?php echo $cant_preguntas ?>
                    </td>
                    <td class="<?= $clases_col['tipo'] ?>">
                        <?= $this->Item_model->nombre(15, $row_resultado->tipo_id) ?>
                        
                    </td>
                    <td class="<?= $clases_col['creado_por'] ?>">
                        <i class="fa fa-user"></i>
                        <?php echo $this->App_model->nombre_usuario($row_resultado->creado_usuario_id) ?>
                        <br/>
                            <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
                                <?= anchor("instituciones/cuestionarios/{$row_resultado->institucion_id}", $this->App_model->nombre_institucion($row_resultado->institucion_id)) ?>
                            <?php } else { ?>
                                <?= $this->App_model->nombre_institucion($row_resultado->institucion_id) ?>
                            <?php } ?>
                    </td>
                    <td class="<?= $clases_col['creado'] ?>">
                        <?php echo $this->Pcrn->fecha_formato($row_resultado->creado, 'Y-M-d'); ?>
                        <br/>
                        <?php echo $this->Pcrn->tiempo_hace($row_resultado->creado, 'Y-M-d'); ?>
                    </td>
                </tr>

            <?php } //foreach ?>
    </tbody>
</table>  