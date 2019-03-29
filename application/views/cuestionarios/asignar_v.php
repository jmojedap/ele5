<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>
<?php $this->load->view('assets/toastr') ?>

<?php
    //Variables para construcción del formulario
        $att_check_todos = array(
            'id' => 'check_todos',
            'name' =>  'check_todos',
            'checked'  =>  FALSE
        );
    
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
?>

<script>
    //Variables
    var base_url = '<?php echo base_url() ?>';
    var cuestionario_id = '<?php echo $row->id ?>';
    var institucion_id = '<?php echo $institucion_id ?>';
    var grupo_id = '<?php echo $grupo_id ?>';
    var validado = false;

// Document Ready
//-----------------------------------------------------------------------------

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
            window.location = base_url + 'cuestionarios/asignar/' + cuestionario_id + '/' + grupo_id;
        });

        //Al modificar se vuelve a validar
        $('#formulario_asignar .form-control').change(function(){
            validar_formulario();
        });

        //Al enviar formulario
        $('#formulario_asignar').submit(function(){
            validar_formulario();
            if ( validado ) {
                asignar();
            } else {
                toastr['warning']('Revise las casillas marcadas en rojo');
            }
            return false;
        });
        
    });

// Funciones
//-----------------------------------------------------------------------------
    function asignar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'cuestionarios/asignar_e/' + cuestionario_id,
            data: $('#formulario_asignar').serialize(),
            success: function(response){
                window.location = base_url + 'cuestionarios/grupos/' + cuestionario_id + '/' + institucion_id + '/' + grupo_id;
                //console.log(response.mensaje);
            }
        });
    }

    function validar_formulario()
    {
        condiciones = 0;
        if ( $('#campo-fecha_inicio').val() <= $('#campo-fecha_fin').val() )
        {
            condiciones++;
            $('#campos-fecha').removeClass('has-error');
        } else {
            $('#campos-fecha').addClass('has-error');
            toastr['warning']('La fecha final debe ser posterior o igual a la fecha inicial');
        }

        //Evaluar cumplimiento de condiciones
        if ( condiciones >= 1 ) { validado = true; }
    }
</script>

<form accept-charset="utf-8" method="POST" id="formulario_asignar">


    <div class="row">
        <div class="col col-md-4" style="min-height: 600px;">
            <div class="card card-default">
                <div class="card-body">
                    <p class="p1">
                        En esta sección puede asignar o editar la asignación de los estudiantes de un grupo al cuestionario
                        <span class="resaltar"><?php echo $row->nombre_cuestionario ?></span>.
                        Si un estudiante ya ha sido agregado previamente al cuestionario no se asignará de nuevo pero se modificarán la fecha
                        inicial y final, y el tiempo para responder.
                    </p>
                    
                    <hr/>

                    <?php if ( $grupo_id > 0 ) { ?>
                        <div class="sep1">
                            <button class="btn btn-success btn-block" type="submit">Asignar</button>
                        </div>
                    <?php } ?>

                
                    <div class="sep1">
                        <label for="grupo_id" class="label1">Grupo</label><br/>
                        <?php echo  form_dropdown('grupo_id', $opciones_grupos, $grupo_id, 'id="grupo_id" class="form-control chosen-select"') ?><br/>
                    </div>
                
                    <?php if ( $grupo_id > 0 ){ ?>
                        <div class="sep1">
                            <label for="tiempo_minutos" class="label1">Tiempo en minutos</label>
                            <p class="descripcion">Tiempo en minutos para que el estudiante resuelva el cuestionario. Mínimo 10 minutos.</p>
                            <input
                                type="number"
                                name="tiempo_minutos"
                                class="form-control"
                                placeholder="Min para resolver cuestionario"
                                title="El tiempo mínimo es de 10 minutos"
                                value="<?php echo $row->tiempo_minutos ?>"
                                required
                                min="10"
                                >
                        </div>

                        <div class="form-group">
                            <label for="fecha_inicio" class="label1">Periodo para responder</label>
                            <p class="descripcion">Fechas entre las cuales los estudiantes pueden responder el cuestionario.</p>
                            
                        </div>

                        <div class="form-group" id="campos-fecha">
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        id="campo-fecha_inicio"
                                        type="date"
                                        name="fecha_inicio"
                                        class="form-control"
                                        placeholder="Fecha inicio"
                                        title="Fecha inicio"
                                        required
                                        value="<?php echo date('Y-m-d') ?>"
                                        >
                                </div>
                                <div class="col-md-6">
                                <input
                                        id="campo-fecha_fin"
                                        type="date"
                                        name="fecha_fin"
                                        class="form-control"
                                        placeholder="Fecha fin"
                                        title="Fecha final para resolver cuestionario"
                                        required
                                        value="<?php echo date('Y-m-d', strtotime('+7 days')) ?>"
                                        >
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                
                
                    <?php if ( validation_errors() ):?>
                        <div class="sep1">
                            <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                        </div>
                    <?php endif ?>

                    <?php if ( $this->session->flashdata('resultado') != NULL ):?>
                        <?php $resultado = $this->session->flashdata('resultado') ?>
                        <div class="sep1">
                            <div class="alert alert-success">Se insertaron <?php echo $resultado['num_insertados'] ?> registros nuevos</div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        
        <div class="col col-md-8">
            <table class="table bg-blanco" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10px"><?php echo form_checkbox($att_check_todos); ?></th>
                        <th>Nombre estudiante</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Minutos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                        <?php
                            $clase_fila = 'table-info';
                        
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
                        <tr class="<?php echo $clase_fila ?>">
                            <td><?php echo form_checkbox($att_check) ?></td>
                            <td><?php echo $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>
                            <td><?php echo $this->Pcrn->fecha_formato($row_uc->fecha_inicio, 'Y-m-d') ?></td>
                            <td><?php echo $this->Pcrn->fecha_formato($row_uc->fecha_fin, 'Y-m-d') ?></td>
                            <td><?php echo $row_uc->tiempo_minutos ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</form>