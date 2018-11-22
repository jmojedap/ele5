<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    //Variables para construcción del formulario
    
    $att_grupo = array(
        'name' => 'grupo',
        'class' =>  'form-control',
        'value' => $row->grupo,
    );
    
    $condicion_opciones = 'anio_generacion = ' . ($row->anio_generacion + 1);
    $condicion_opciones .= " AND institucion_id = {$row->institucion_id}";
    $opciones_grupo = $this->App_model->opciones_grupo($condicion_opciones);
    
    
        $att_submit = array(
            'value' =>  'Guardar',
            'class' => 'btn btn-primary'
        );

    $clase_botones = array('', '', '');
    $clase_botones[$tipo_promocion] = 'active';
    
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

?>

<script>
    $(document).ready(function(){
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked"))
            {
                //Activado
                $('.check_registro').iCheck('check');
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
            }
        });
    });
</script>

<?= form_open($destino_form) ?>

    <?= form_hidden('nivel', $row->nivel + 1) ?>
    <?= form_hidden('institucion_id', $row->institucion_id) ?>
    <?= form_hidden('anio_generacion', $row->anio_generacion + 1) ?>
    <?= form_hidden('anterior_grupo_id', $row->id) ?>

    <div class="row">
        <div class="col col-md-3">

            <div class="sep1">
                <ul class="nav nav-pills">
                    <li role="presentation" class="<?= $clase_botones[1] ?>">
                        <?= anchor("grupos/promover/$row->id/1", 'Grupo nuevo'); ?>
                    </li>
                    <li role="presentation" class="<?= $clase_botones[2] ?>">
                        <?= anchor("grupos/promover/$row->id/2", 'Grupo existente'); ?>
                    </li>
                </ul>
            </div>

            <div class="panel panel-default" style="min-height: 400px;">
                <div class="panel-heading">
                    Grupo destino
                </div>
                <div class="panel-body">
                    <p>Puede promover los estudiantes a un grupo nuevo o a un grupo existente.</p>

                        <?php if ( $tipo_promocion == 1 ): ?>
                            <p>
                                <span class="suave">Institución: </span>
                                <span class="resaltar"><?= $this->App_model->nombre_institucion($row->institucion_id) ?></span> |
                                <span class="suave">Nivel: </span>
                                <span class="resaltar"><?= $this->Item_model->nombre(3, $row->nivel + 1) ?></span> |
                                <span class="suave">Año generación: </span>
                                <span class="resaltar"><?= $row->anio_generacion + 1 ?></span> |
                            </p>

                            <div class="sep1">
                                <label for="grupo" class="label1">Grupo Nuevo</label>
                                <p class="descripcion">Número o letra que identifica al grupo</p>
                                <?=  form_input($att_grupo) ?>
                            </div>

                        <?php endif ?>

                        <?php if ( $tipo_promocion == 2 ):?>
                            <div class="sep1">
                                <label for="grupo" class="label1">Grupo existente</label>
                                <p class="descripcion">Grupo de estudiantes creado previamente, correspondientes al año <?= $row->anio_generacion + 1 ?></p>
                                <?=  form_dropdown('grupo_existente_id', $opciones_grupo, '', 'class="form-control chosen-select"') ?>
                            </div>
                        <?php endif ?>

                    <?php if ( $tipo_promocion > 0 ):?>
                        <div class="sep1">
                            <?= form_submit($att_submit) ?>        
                        </div>
                    <?php endif ?>

                    <?php if ( validation_errors() ):?>
                        <div class="alert alert-danger">
                            Error de validación
                        </div>
                        <?= validation_errors('<div class="alert alert-danger" role="alert">', '</div>') ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col col-md-9">
            <table class="table table-default bg-blanco">
                <thead>
                    <tr>
                        <th width="10px"><?= form_checkbox($att_check_todos) ?></th>
                        <th>Nombre estudiante</th>
                        <th>Promovido a <?= $row->anio_generacion + 1 ?></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                        <?php
                            $row_usuario = $this->Pcrn->registro('usuario', "id = {$row_estudiante->id}");
                            $anio_actual = $this->Pcrn->campo('grupo', "id = {$row_usuario->grupo_id}", 'anio_generacion');

                            $promovido = 0;
                            if ( $anio_actual == ( $row->anio_generacion + 1 ) ) { $promovido = 1; }
                            
                            $clase_fila = '';
                            if ( $promovido ) { $clase_fila = 'success'; }
                            
                            //Checkbox
                                $att_check['name'] = $row_estudiante->id;
                                $att_check['value'] = $this->Pcrn->si_cero($promovido, TRUE, FALSE);
                        ?>

                        <tr class="<?= $clase_fila ?>">
                            <td><?= form_checkbox($att_check) ?></td>
                            <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>
                            <td>
                                <?php if ( $promovido ) { ?>
                                    <i class="fa fa-check-circle"></i> Sí
                                <?php } else { ?>
                                    -
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach ?>


                </tbody>
            </table>
        </div>
    </div>

<?= form_close() ?>