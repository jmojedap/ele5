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

<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="50px;">ID</th>
                <th>Cuestionario</th>
                
                <th class="<?= $clases_col['nivel'] ?>" style="min-width: 200px;">Nivel Área</th>
                <th class="<?= $clases_col['preguntas'] ?>">Preguntas</th>
                <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
                <th class="<?= $clases_col['creado'] ?>">Creado por</th>
                <th class="<?= $clases_col['editado'] ?>">Creado</th>
                
                <th width="35px" class="hidden-xs"></th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre_cuestionario;
                    $link_elemento = anchor("cuestionarios/vista_previa/$row_resultado->id", $nombre_elemento);
                    $editable = $this->Cuestionario_model->editable($row_resultado->id);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;
                    
                //Otras
                    $cant_preguntas = $this->Cuestionario_model->num_preguntas($row_resultado->id);

            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    
                    <td class="warning"><?= $row_resultado->id ?></td>
                    
                    <td>
                        <?php echo $link_elemento ?>
                    </td>
                    
                    <td class="<?= $clases_col['nivel'] ?>">
                        <span class="etiqueta nivel w1"><?= $row_resultado->nivel ?></span>
                        <?= $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                    </td>
                    <td class="<?= $clases_col['preguntas'] ?>">
                        <?= $cant_preguntas ?>
                    </td>
                    <td class="<?= $clases_col['tipo'] ?>">
                        <?= $this->Item_model->nombre(15, $row_resultado->tipo_id) ?>
                        
                    </td>
                    <td class="<?= $clases_col['creado'] ?>">
                        <?= $this->App_model->nombre_usuario($row_resultado->creado_usuario_id) ?>
                        <br/>
                            <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
                                <?= anchor("instituciones/cuestionarios/{$row_resultado->institucion_id}", $this->App_model->nombre_institucion($row_resultado->institucion_id)) ?>
                            <?php } else { ?>
                                <?= $this->App_model->nombre_institucion($row_resultado->institucion_id) ?>
                            <?php } ?>
                    </td>
                    <td class="<?= $clases_col['editado'] ?>">
                        <?php echo $this->Pcrn->fecha_formato($row_resultado->creado, 'Y-M-d'); ?>
                        <br/>
                        <?php echo $this->Pcrn->tiempo_hace($row_resultado->creado, 'Y-M-d'); ?>
                    </td>
                    
                    <td class="hidden-xs">
                        <?php if ( $editable ){ ?>
                            <?php echo anchor("cuestionarios/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil-alt"></i>', 'class="btn btn-sm btn-light" role="button"') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
    </tbody>
</table>  

<?= $this->load->view('app/modal_eliminar'); ?>