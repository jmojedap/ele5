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

?>

<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <th width="10px;" class="<?= $clases_col['selector'] ?>"><?= form_checkbox($att_check_todos) ?></th>
        <th width="60px;">ID</th>

        <th class="<?= $clases_col['grupo'] ?>" width="50px">Grupos</th>
        <th class="<?= $clases_col['institucion'] ?>">Institución</th>
        
        <th class="<?= $clases_col['cant_estudiantes'] ?>">Estudiantes</th>
        <th class="<?= $clases_col['anio_generacion'] ?>">Año</th>

        <th width="35px" class="<?= $clases_col['botones'] ?>"></th>
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre_grupo;
                    $link_elemento = anchor("grupos/index/{$row_resultado->id}", $nombre_elemento, 'class="btn btn-primary w3"');

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;

                //Otros datos
                    $cant_estudiantes = $this->Grupo_model->cant_estudiantes($row_resultado->id);
                    $pct = $this->Pcrn->int_percent($cant_estudiantes, 60);
            ?>
            <tr>
                <td class="<?= $clases_col['selector'] ?>">
                    <?= form_checkbox($att_check) ?>
                </td>

                <td class="warning text-right"><?= $row_resultado->id ?></td>

                <td>
                    <?= $link_elemento ?>
                </td>

                <td class="<?= $clases_col['institucion'] ?> ?>">
                    <?= $this->App_model->nombre_institucion($row_resultado->institucion_id) ?>
                </td>
                
                <td class="<?= $clases_col['cant_estudiantes'] ?>">
                    <?= $cant_estudiantes ?>
                </td>
                <td class="<?= $clases_col['anio_generacion'] ?>">
                    <?= $row_resultado->anio_generacion ?>
                </td>


                <td class="<?= $clases_col['botones'] ?>">
                    <?= anchor("grupos/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                </td>
            </tr>

        <?php } //foreach ?>
    </tbody>
</table>

<?php $this->load->view('app/modal_eliminar'); ?>