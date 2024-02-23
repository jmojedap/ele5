<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    $opciones_flipbooks = $this->App_model->opciones_flipbook("tipo_flipbook_id IN (0,3,6) AND nivel = {$row->nivel}", 2);
?>

<script>
    $(document).ready(function(){
        $('#check_todos').change(function() {
            if($(this).is(":checked")) {
                $('form input[type=checkbox]').each( function() {			
                    this.checked = true;
                });
            } else {
                $('form input[type=checkbox]').each( function() {			
                    this.checked = false;
                });
            }
        });
    });
</script>

<?php $this->load->view('grupos/submenu_flipbooks_v') ?>

<?= form_open("grupos/validar_asignacion_f/{$row->id}") ?>

    <div class="row">
        <div class="col col-md-3">
        <div class="card card-default">
            <div class="card-body" style="min-height: 400px;">
            <div class="mb-2">
                    <label for="flipbook_id" class="label1">Contenido</label>
                    <?=  form_dropdown('flipbook_id', $opciones_flipbooks, set_value('flipbook_id'), 'class="form-control chosen-select"') ?><br/>
                </div>
                <div class="mb-2">
                    <button class="btn btn-primary w120p" type="submit">
                        Asignar
                    </button>
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
            <div class="card mb-2">
                <div class="card-body">
                    <p>
                        Los estudiantes que se seleccionen serán asignados al Contenido.
                        Si un estudiante ya ha sido agregado previamente al contenido no se asignará de nuevo y sus datos
                        permanecerán sin modificaciones.
                    </p>
                </div>
            </div>
            
            <table class="table table-default bg-white" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10px"><input type="checkbox" id="check_todos"></th>
                        <th>Nombre estudiante</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="<?= $row_estudiante->id ?>" class="check_registro">
                            </td>
                            <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

<?= form_close() ?>