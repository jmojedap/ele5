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
        $clases_col['botones'] = '';
        $clases_col['selector'] = '';
        $clases_col['editado'] = 'hidden-sm hidden-xs';
        $clases_col['creado'] = 'hidden-sm hidden-xs';
        $clases_col['botones'] = 'hidden-sm hidden-xs';
        
        $clases_col['tipo'] = '';
        
        if ( $this->session->userdata('rol_id') >= 3 )
        {
            $clases_col['selector'] = 'hidden';
            $clases_col['botones'] = 'hidden';
        }
        
    //Clases columnas orden
        if ( $busqueda['o'] == 'tipo_id' ) { $clases_head['tipo'] = 'info'; }
        
    //Links orden encabezados
        $encabezados = array('id');
        $orden_alt = $this->Pcrn->alternar($this->input->get('ot'), 'asc', 'desc');
        $b_sin_orden = $this->Pcrn->get_str('o,ot');
        
        foreach ( $encabezados as $encabezado )
        {
            $links_orden[$encabezado] = "{$cf}?{$b_sin_orden}&o={$encabezado}&ot={$orden_alt}";
        }
?>


<table class="table table-default bg-blanco" cellspacing="0">
    
    <thead>
        <th class="<?= $clases_col['selector'] ?>" width="10px;">
            <?= form_checkbox($att_check_todos) ?>
        </th>
        <th width="45px;" class="warning">
            <?= anchor($links_orden['id'], 'ID', 'title="Ordenar por ID"') ?>
        </th>
        <th>
            Post
        </th>
        
        <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
        <th class="<?= $clases_col['editado'] ?>">Editado</th>
        <th class="<?= $clases_col['creado'] ?>">Creado</th>
        
        <th class="<?= $clases_col['botones'] ?>" width="10px"></th>
    </thead>
    
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $this->Pcrn->si_strlen($row_resultado->nombre_post, 'Post ' . $row_resultado->id);
                    $link_elemento = anchor("posts/index/$row_resultado->id", $nombre_elemento);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;
                    
                //Otros
                    $get_str_sin_dcto = $this->Pcrn->get_str('dcto');
            ?>
            <tr>
                <td>
                    <?= form_checkbox($att_check) ?>
                </td>
                <td class="warning"><?= $row_resultado->id ?></td>
                
                <td><?= $link_elemento ?></td>
                
                <td class="<?= $clases_col['tipo'] ?>">
                    <?= $arr_tipos[$row_resultado->tipo_id] ?>
                </td>
                <td class="<?= $clases_col['editado'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->editado); ?><br/>
                    <?= $this->App_model->nombre_usuario($row_resultado->editor_id, 2); ?>
                </td>
                <td class="<?= $clases_col['creado'] ?>">
                    <?= $this->Pcrn->fecha_formato($row_resultado->creado); ?><br/>
                    <?= $this->App_model->nombre_usuario($row_resultado->usuario_id, 2); ?>
                </td>
                
                <td class="<?= $clases_col['botones'] ?>">                    
                    <?= anchor("usuarios/editar/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>

<?= $this->load->view('app/modal_eliminar'); ?>