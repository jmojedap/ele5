<?php

    //Variables para construcción del formulario
    
    
    $opciones_archivos = $this->App_model->opciones_archivo("id > 0", 1);
    
    $submit = array(
        'value' =>  'Asignar',
        'class' => 'button white'
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

<?= form_open("grupos/validar_asignacion_a/{$row->id}") ?>

<article class="module width_3_quarter">
    <header>
        <h3>Datos de asignación</h3>    
    </header>
    
    <div class="module_content">
        <?= anchor("instituciones/estudiantes/{$row->institucion_id}/{$row->id}", "Volver", 'class="a2"') ?>
        
    </div>
    
    <hr />
    
    
    
    <div class="module_content">

        <div class="div1">
            <label for="archivo_id" class="label1">Archivo</label>
            <?=  form_dropdown('archivo_id', $opciones_archivos, set_value('archivo_id'), 'class="select-1"') ?><br/>
        </div>
    </div>
    
    <footer>
        <div class="submit_link">
            <?= form_submit($submit) ?>        
        </div>
    </footer>
    
    
    
</article>

<?php if ( validation_errors() ):?>
    <div class="modulo2 width_3_quarter">
        <?= validation_errors('<h4 class="alert_error">', '</h4>') ?>
    </div>
<?php endif ?>

<?php if ( $this->session->flashdata('resultado') != NULL ):?>
    <?php $resultado = $this->session->flashdata('resultado') ?>
    <div class="modulo2 width_3_quarter">
        <h4 class="alert_success">Se insertaron <?= $resultado['num_insertados'] ?> registros nuevos</h4>
    </div>
<?php endif ?>

<article class="module width_3_quarter">
    <header>
        <h3>Estudiantes a asignar</h3>
    </header>
    
    <div class="module_content">
        <p class="p1">
            Los estudiantes que se seleccionen serán asignados al archivo.
            Si un estudiante ya ha sido agregado previamente al archivo no se asignará de nuevo y sus datos
            permanecerán sin modificaciones.
        </p>
    </div>

    <hr/>
    
    <div class="module_content">
        <p>
            <span class="button white small" id="todos_check">Todos</span>
            <span class="button white small" id="ninguno_check">Ninguno</span>
        </p>
    </div>

    <hr/>
    
    <table class="tablesorter" cellspacing="0">
        <thead>
            <tr>
                <th>Asignar</th>
                <th>Nombre estudiante</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                <tr>
                    <td width="50px"><?= form_checkbox($row_estudiante->id, 1, TRUE) ?></td>
                    <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>
                </tr>
            <?php endforeach ?>


        </tbody>
    </table>


</article>

<?= form_close() ?>