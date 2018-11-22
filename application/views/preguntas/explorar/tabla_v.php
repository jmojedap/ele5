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
        $clases_col['selector'] = 'hidden';
        $clases_col['cant_estudiantes'] = 'hidden-sm hidden-xs';
        $clases_col['botones'] = 'hidden-sm hidden-xs';
        $clases_col['extras'] = 'hidden-sm hidden-xs';
        
        if ( $this->session->userdata('rol_id') >= 3 )
        {
            $clases_col['selector'] = 'hidden';
            $clases_col['botones'] = 'hidden';
        }
        
        if ( $this->session->userdata('rol_id') <= 2 )
        {
            $clases_col['selector'] = '';
        }
        
    //Arrays con valores para contenido en lista
        $arr_tipos = $this->Item_model->arr_interno('categoria_id = 161');
        $arr_nivel = $this->Item_model->arr_interno('categoria_id = 3');
        $arr_area = $this->Item_model->arr_interno('categoria_id = 1');

?>

<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <th width="10px;" class="<?= $clases_col['selector'] ?>"><?= form_checkbox($att_check_todos) ?></th>
        <th width="60px;">ID</th>

        <th class="<?= $clases_col['pregunta'] ?>">Pregunta</th>
        <th class="<?= $clases_col['texto_pregunta'] ?>">Texto pregunta</th>
        <th class="<?= $clases_col['nivel_area'] ?>">Nivel - Área</th>
        <th class="<?= $clases_col['edicion'] ?>">Editado por</th>

        <th width="35px" class="<?= $clases_col['botones'] ?>"></th>
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = "Pregunta " . $row_resultado->id;
                    $link_elemento = anchor("preguntas/index/$row_resultado->id", $nombre_elemento);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;
            ?>
            <tr>
                <td class="<?= $clases_col['selector'] ?>">
                    <?= form_checkbox($att_check) ?>
                </td>

                <td class="warning text-right"><?= $row_resultado->id ?></td>

                <td>
                    <?= $link_elemento ?>
                </td>

                <td class="<?= $clases_col['texto_pregunta'] ?>">
                    <?= word_limiter($row_resultado->texto_pregunta, 20) ?>
                </td>
                
                <td class="<?= $clases_col['nivel_area'] ?>">
                    <span class="etiqueta nivel w1"><?= $row_resultado->nivel ?></span>
                    <?= $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                </td>
                
                <td class="<?= $clases_col['edicion'] ?>">
                    <?= $this->App_model->nombre_usuario($row_resultado->editado_usuario_id, 2); ?>
                    <br/>
                    <span class="suave">
                        <?= $this->Pcrn->fecha_formato($row_resultado->editado, 'Y-m-d') ?> | 
                        <?= $this->Pcrn->tiempo_hace($row_resultado->editado, TRUE) ?>
                    </span>
                </td>

                <td class="<?= $clases_col['botones'] ?>">
                    <?= anchor("preguntas/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                </td>
            </tr>

        <?php } //foreach ?>
    </tbody>
</table>

<?= $this->load->view('app/modal_eliminar'); ?>