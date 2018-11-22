<?php
    $att_form = array(
        'class' =>  'form-horizontal'
    );
    
    $att_nombre_cuestionario = array(
        'name'  =>  'nombre_cuestionario',
        'value' => 'Cuestionario - ' . $row->nombre_programa . ' - ' . date('Ymdhis'),
        'class' =>  'form-control',
        'required' =>  TRUE
    );
    
    $att_unidad = array(
        'name'  =>  'unidad',
        'value' => set_value('unidad'),
        'class' =>  'form-control',
        'type'  =>  'number',
        'min'   =>  '1',
        'max'   =>  '12'
    );
    
    $opciones_privado = $this->Item_model->opciones('categoria_id = 55 AND id_interno < 2');
    $opciones_prueba_periodica = $this->Item_model->opciones('categoria_id = 55 AND id_interno < 2');
    
    $att_descripcion = array(
        'name'  =>  'descripcion',
        'value' => 'Cuestionario - creado a partir del programa: ' . $row->nombre_programa . ', creado el ' . date('Y-M-d h:i:s'),
        'class' =>  'form-control',
        'rows'  =>  3
        
    );
    
    $att_submit = array(
        'value' => 'Generar',
        'class' =>  'btn btn-primary'
    );
?>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>
<?php $this->load->view('comunes/validation_errors_v'); ?>

<?= form_open($destino_form, $att_form) ?>
    <?= form_hidden('nivel', $row->nivel) ?>
    <?= form_hidden('area_id', $row->area_id) ?>

    <div class="panel panel-default">
        <div class="panel-body">
            
            <div class="row">
                <div class="col col-sm-9 col-sm-offset-3">
                    <p>
                        El cuestionario generado tendrá las <span class="resaltar"><?= $preguntas->num_rows() ?></span>
                        preguntas asociadas a los temas del programa.
                    </p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="nombre_cuestionario" class="col-sm-3 control-label">Nombre cuestionario</label>
                <div class="col-sm-9">
                    <?= form_input($att_nombre_cuestionario) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="unidad" class="col-sm-3 control-label">Unidad</label>
                <div class="col-sm-9">
                    <?= form_input($att_unidad) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="privado" class="col-sm-3 control-label">Privado</label>
                <div class="col-sm-9">
                    <?= form_dropdown('privado', $opciones_privado, '0', 'class="form-control" title="Especificar si el cuestionario será visible para otras instituciones"') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="prueba_periodica" class="col-sm-3 control-label">Es prueba periódica</label>
                <div class="col-sm-9">
                    <?= form_dropdown('prueba_periodica', $opciones_prueba_periodica, '0', 'class="form-control" title="¿El cuestionario es una prueba periódica?"') ?>
                </div>
            </div>
            <div class="form-group">
                <label for="notas" class="col-sm-3 control-label">Notas</label>
                <div class="col-sm-9">
                    <?= form_textarea($att_descripcion) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        </div>
    </div>

<?= form_close('') ?>
