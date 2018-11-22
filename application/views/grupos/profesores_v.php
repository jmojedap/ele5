<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    //Permisos
        $display_form = 'none';
        $editar = FALSE;
        if ( $this->session->userdata('rol_id') <= 2  ) {
            $editar = TRUE;
            $display_form = 'inline';
        }
        
    //Formularios
    
        $att_form = array(
            'class' => 'form-horizontal'
        );

        //$opciones_profesor = $this->Pcrn->opciones_dropdown($usuarios, 'id', 'username', 'Seleccione el profesor');
        $opciones_areas = $this->Item_model->opciones_id('categoria_id = 1', 'Seleccione el área');
        
        $opciones_profesor[''] = '[ Selecccione el profesor ]';
        foreach ( $usuarios->result() as $row_usuario ) {
            $opciones_profesor['0' . $row_usuario->id] = $row_usuario->nombre . ' ' . $row_usuario->apellidos . ' (' . $row_usuario->username . ')';
        }

        $att_submit = array(
            'value' =>  'Agregar',
            'class' =>  'btn btn-primary'
        );
        
    //
?>

<div class="row">
    
    <div class="col col-md-8">
        <table class="table table-default bg-blanco">
            <thead>
                <th>Profesor</th>
                <th>Área</th>
                <?php if ( $editar ){ ?>
                    <th width="35px" class="centrado"></th>
                <?php } ?>
            </thead>
            <tbody>
                <?php foreach ($profesores->result() as $row_profesor) : ?>
                    <?php
                        $clase_fila = '';
                        if ( $row_profesor->gp_id == $gp_id ) { $clase_fila = 'success'; }
                    ?>
                    <tr class="<?= $clase_fila ?>">
                        <td>
                            <?= anchor("usuarios/grupos_profesor/{$row_profesor->profesor_id}", $this->App_model->nombre_usuario($row_profesor->profesor_id, 3)) ?>
                        </td>
                        <td>
                            <?= $this->App_model->etiqueta_area($row_profesor->area_id); ?>
                        </td>

                        <?php if ( $editar ){ ?>
                            <td class="centrado">
                                <?= anchor("grupos/quitar_profesor/{$row->id}/{$row_profesor->id}", '<i class="fa fa-times"></i>', 'class="a4" title"Quitar asignación de profesor"') ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    
    <div class="col col-md-4" style="min-height: 600px;">
        <?php if ( $this->session->userdata('rol_id') <= 4 ) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Agregar profesor al grupo
                </div>
                <div class="panel-body">


                        <?= form_open($destino_form, $att_form) ?>
                            <div class="form-group">
                                <label for="profesor_id" class="col-md-3 control-label">Profesor</label>
                                <div class="col-md-9">
                                    <?= form_dropdown('profesor_id', $opciones_profesor, set_value('profesor_id'), 'class="form-control chosen-select" required'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="area_id" class="col-md-3 control-label">Área</label>
                                <div class="col-md-9">
                                    <?= form_dropdown('area_id', $opciones_areas, set_value('area_id'), 'class="form-control chosen-select" required'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <?= form_submit($att_submit) ?>
                                </div>
                            </div>
                        <?= form_close('') ?>


                </div>
            </div>
        <?php } ?>
        
    </div>
    
</div>