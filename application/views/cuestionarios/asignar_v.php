<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    //Variables para construcción del formulario
    
    $att_form = array(
        'class' =>  'form1'
    );
    
    $att_check_todos = array(
        'id' => 'check_todos',
        'name' =>  'check_todos',
        'checked'  =>  FALSE
    );

    
    $anio_generacion = $this->Pcrn->si_vacia($row->anio_generacion, date('Y'));
    
    /*$condicion_grupos = "anio_generacion >= {$anio_generacion} AND ";
    if ( $institucion_id > 0 ) { $condicion_grupos .= "institucion_id = {$institucion_id} AND "; }
    $condicion_grupos .= "nivel = {$row->nivel}";*/
    
    //Selección de grupos
        $condicion_grupos = "nivel = {$row->nivel}";
    
        if ( $this->session->userdata('srol') != 'interno' ) 
        {
            $str_grupos = '0';
            $arr_grupos = $this->session->userdata('arr_grupos');
            if ( count($arr_grupos) > 0 ) { $str_grupos = implode(',', $arr_grupos); }
            $condicion_grupos = 'grupo.id IN (' . $str_grupos . ')';
        }
        
    
    $opciones_grupos = $this->App_model->opciones_grupo($condicion_grupos, 1);
    
    $fecha_inicio = array(
        'name' => 'fecha_inicio',
        'class' =>  'form-control',
        'value' => date('Y-m-d'),
        'type'  =>  'date',
        'required' => TRUE
    );
    
    $fecha_fin = array(
        'name' => 'fecha_fin',
        'class' =>  'form-control',
        'value' => date('Y-m-d', strtotime('+7 days')),
        'type'  =>  'date',
        'required' => TRUE
    );
    
    $tiempo_minutos = array(
        'name' => 'tiempo_minutos',
        'class' =>  'form-control',
        'value' => $row->tiempo_minutos,
        'required' => TRUE
    );
    
    $submit = array(
        'value' =>  'Asignar',
        'class' => 'btn btn-primary'
    )

?>

<script>
    //Variables
    var base_url = '<?= base_url() ?>cuestionarios/asignar/<?= $row->id ?>/';
    var grupo_id = 0;
</script>
    

<script>
    $(document).ready(function(){
        $('#todos').change(function(){
            if( $(this).is(":checked") ) {
                $('form input[type=checkbox]').each( function() {			
                    this.checked = true;
                });
            } else {
                $('form input[type=checkbox]').each( function() {			
                    this.checked = false;
                });
            }
        });
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').iCheck('check');
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
            }
        });
        
        $('#grupo_id').change(function(){
            grupo_id = $(this).val();
            window.location = base_url + grupo_id;
        });
        
    });
</script>

<?= form_open($destino_form, $att_form) ?>

<div class="row">
    <div class="col col-md-4" style="min-height: 600px;">
        <div class="panel panel-default">
            <div class="panel-body">
                <p class="p1">
                    En esta sección puede asignar o editar la asignación de los estudiantes de un grupo al cuestionario
                    <span class="resaltar"><?= $row->nombre_cuestionario ?></span>.
                    Si un estudiante ya ha sido agregado previamente al cuestionario no se asignará de nuevo pero se modificarán la fecha
                    inicial y final, y el tiempo para responder.
                </p>
                
                <hr/>
            
                <div class="sep1">
                    <label for="grupo_id" class="label1">Grupo</label><br/>
                    <?=  form_dropdown('grupo_id', $opciones_grupos, $grupo_id, 'id="grupo_id" class="form-control chosen-select"') ?><br/>
                </div>
            
                <?php if ( $grupo_id > 0 ){ ?>
                    <div class="sep1">
                        <label for="tiempo_minutos" class="label1">Tiempo en minutos</label>
                        <p class="descripcion">Tiempo en minutos para que el estudiante resuelva el cuestionario. Mínimo 10 minutos.</p>
                        <?=  form_input($tiempo_minutos) ?>
                    </div>

                    <div class="sep1">
                        <label for="fecha_inicio" class="label1">Fecha inicio</label>
                        <p class="descripcion">Fecha desde la cual los estudiantes pueden responder el cuestionario</p>
                        <?=  form_input($fecha_inicio) ?>
                    </div>



                    <div class="sep1">
                        <label for="fecha_fin" class="label1">Fecha fin</label>
                        <p class="descripcion">Fecha hasta la cual los estudiantes pueden responder el cuestionario</p>
                        <?=  form_input($fecha_fin) ?>
                    </div>

                    <div class="sep1">
                        <?= form_submit($submit) ?>        
                    </div>
                <?php } ?>
            
            
                <?php if ( validation_errors() ):?>
                    <div class="sep1">
                        <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>
                    </div>
                <?php endif ?>

                <?php if ( $this->session->flashdata('resultado') != NULL ):?>
                    <?php $resultado = $this->session->flashdata('resultado') ?>
                    <div class="sep1">
                        <div class="alert alert-success">Se insertaron <?= $resultado['num_insertados'] ?> registros nuevos</div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    
    <div class="col col-md-8">
        <table class="table bg-blanco" cellspacing="0">
            <thead>
                <tr>
                    <th width="10px"><?= form_checkbox($att_check_todos); ?></th>
                    <th>Nombre estudiante</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Minutos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                    <?php
                        $clase_fila = 'info';
                    
                        //Registro asignación
                            $condicion = "usuario_id = {$row_estudiante->id} AND cuestionario_id = {$row->id}";
                            $row_uc = $this->Pcrn->registro('usuario_cuestionario', $condicion);
                        
                        //Checkbox
                            $att_check['name'] = $row_estudiante->id;
                            $att_check['class'] = 'check_registro';
                            $att_check['value'] = 1;
                            $att_check['checked'] = FALSE;
                            
                        if ( is_null($row_uc) ) {
                            $att_check['checked'] = TRUE;
                            $clase_fila = '';
                        }
                        
                    ?>
                    <tr class="<?= $clase_fila ?>">
                        <td><?= form_checkbox($att_check) ?></td>
                        <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>
                        <td><?= $this->Pcrn->fecha_formato($row_uc->fecha_inicio, 'Y-m-d') ?></td>
                        <td><?= $this->Pcrn->fecha_formato($row_uc->fecha_fin, 'Y-m-d') ?></td>
                        <td><?= $row_uc->tiempo_minutos ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= form_close() ?>