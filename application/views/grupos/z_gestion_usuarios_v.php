<?php

    //Variables para construcción del formulario

    $opciones_proceso = array(
        'p1' => 'Activar',
        'p2' => 'Desactivar',
        'p3' => 'Restaurar contraseña'
    );
    
    if ( $this->session->userdata('usuario_id') > 2 ){
        $opciones_proceso = array(
            'p3' => 'Restaurar contraseña'
        );
    }
    
    $submit = array(
        'value' =>  'Ejecutar',
        'class' => 'button orange'
    )

?>

<script>
    $(document).ready(function(){
        $('#todos_check').click(function(){
            $('form input[type=checkbox]').each( function() {			
                this.checked = true;
            });
        });
        
        $('#ninguno_check').click(function(){
            $('form input[type=checkbox]').each( function() {			
                this.checked = false;
            });
        });
    });
</script>

<?php $this->load->view('grupos/submenu_estudiantes_v') ?>

<?= form_open("grupos/ejecutar_proceso/{$row->id}") ?>

<div class="seccion group">
    <div class="col col_box span_1_of_3">
        <div class="info_container_body">  
            <div class="div1">
                <label for="cuestionario_id" class="label1">Proceso de usuarios</label>
                <?=  form_dropdown('proceso', $opciones_proceso, set_value('proceso'), 'class="select-1"') ?><br/>
            </div>
            <div class="div1">
                <?= form_submit($submit) ?>        
            </div>
            <?php if ( validation_errors() ):?>
                <div class="modulo2 width_full">
                    <?= validation_errors('<h4 class="alert_error">', '</h4>') ?>
                </div>
            <?php endif ?>

            <?php if ( $this->session->flashdata('resultado') != NULL ):?>
                <?php $resultado = $this->session->flashdata('resultado') ?>
                <div class="modulo2 width_full">
                    <h4 class="alert_success"><?= $resultado['proceso'] ?>: Se procesaron <?= $resultado['num_procesados'] ?> usuarios</h4>
                </div>
            <?php endif ?>
        </div>
    </div>
    
    <div class="col col_box span_2_of_3">
        <div class="info_container_body">
            <h3>Usuarios a procesar</h3>
            <p class="p1">
                A los estudiantes que se seleccionen se les ejecutará el proceso elegido. Al desactivar un usuario también se <span class="resaltar">restaurará su contraseña</span> al valor por defecto.
            </p>
            <p>
                <span class="button white small" id="todos_check">Todos</span>
                <span class="button white small" id="ninguno_check">Ninguno</span>
            </p>
            <hr/>

            <table class="tablesorter" cellspacing="0">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Nombre estudiante</th>
                        <th>Username</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                        <?php
                            $valor_activo = $this->Pcrn->campo('usuario', "id = {$row_estudiante->id}", 'inactivo');
                            $valor_activo = $this->Pcrn->si_cero($valor_activo, 'Sí', '<span class="resaltar">No</span>');
                        ?>

                        <tr>
                            <td width="50px"><?= form_checkbox($row_estudiante->id, 1, TRUE) ?></td>
                            <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>
                            <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 1) ?></td>
                            <td><?= $valor_activo ?></td>
                        </tr>
                    <?php endforeach ?>


                </tbody>
            </table>
        </div>
    </div>
</div>
<article class="module width_full">
    
        


</article>

<?= form_close() ?>