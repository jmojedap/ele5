<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    //Variables para construcción del formulario

    $opciones_flipbooks = $this->App_model->opciones_flipbook("tipo_flipbook_id IN (0,3)  AND nivel = {$row->nivel}", 2);
    
    $att_submit = array(
        'value' =>  'Asignar',
        'class' =>  'btn btn-primary'
    );
    
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
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
    });
</script>

<?php $this->load->view('grupos/submenu_flipbooks_v') ?>

<?= form_open("grupos/validar_asignacion_f/{$row->id}") ?>

    <div class="row">
        <div class="col col-md-3">
        <div class="panel panel-default">
            <div class="panel-body" style="min-height: 400px;">
                <div class="sep1">
                    <label for="flipbook_id" class="label1">Contenido</label>
                    <?=  form_dropdown('flipbook_id', $opciones_flipbooks, set_value('flipbook_id'), 'class="form-control chosen-select"') ?><br/>
                </div>
                <div class="sep1">
                    <?= form_submit($att_submit) ?>        
                </div>

                <?php if ( validation_errors() ):?>
                    <div class=" width_full">
                        <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
                    </div>
                <?php endif ?>

                <?php if ( $this->session->flashdata('resultado') != NULL ):?>
                    <?php $resultado = $this->session->flashdata('resultado') ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fa fa-info-circle"></i>
                        Se insertaron <?= $resultado['num_insertados'] ?> registros nuevos
                    </div>
                <?php endif ?>
            </div>
        </div>
        </div>
        <div class="col col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p class="p1">
                        Los estudiantes que se seleccionen serán asignados al Contenido.
                        Si un estudiante ya ha sido agregado previamente al contenido no se asignará de nuevo y sus datos
                        permanecerán sin modificaciones.
                    </p>
                </div>
            </div>
            
            <table class="table table-default bg-blanco" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                        <th>Nombre estudiante</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                        <?php
                            //Check
                            $att_check['name'] = $row_estudiante->id;
                        ?>
                        <tr>
                            <td width="50px"><?= form_checkbox($att_check) ?></td>
                            <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

<?= form_close() ?>