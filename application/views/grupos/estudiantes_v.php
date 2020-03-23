<?php $this->load->view('assets/icheck'); ?>
<?php $this->load->view('assets/toastr') ?>

<?php

    //Variables para construcción del formulario

    $opciones_proceso = array(
        'p1' => 'Activar',
        'p2' => 'Desactivar',
        'p3' => 'Restaurar contraseña',
        'p4' => 'Eliminar',
        'p8' => 'Retirar (Sin eliminar)',
        'p5' => 'Marcar como pagado',
        'p6' => 'Marcar como NO pagado',
    );
    
    $arr_sexo = $this->Item_model->arr_item(59);
    
    //Se excluye a directivo,  (2020-03-21)
    if ( in_array($this->session->userdata('rol_id'), array(2,3,5)) ){
        $opciones_proceso = array(
            'p3' => 'Restaurar contraseña'
        );
    }
    
    if ( $this->session->userdata('rol_id') == 8 ){
        $opciones_proceso = array(
            'p3' => 'Restaurar contraseña',
            'p5' => 'Marcar como pagado'
        );
    }
    
    if ( $this->session->userdata('srol') == 'interno' ) 
    {
        foreach ( $grupos_nivel->result() as $row_grupo ) 
        {
            $indice = 'p7-' . substr('00000' . $row_grupo->id, -6, 6);
            //$indice = 'p7-' . $row_grupo->id;
            $opciones_proceso[$indice] = "Mover al grupo {$row_grupo->nivel}-{$row_grupo->grupo}";
        }
    }
    
            
    $att_check_todos = array(
        'name' => 'check_todos',
        'id'    => 'check_todos',
        'checked' => FALSE
    );
    
    $att_check = array(
        'class' =>  'check_registro',
        'value' => 1,
        'checked' => FALSE
    );
    
    //Elementos usuario.pago
        $pago_texto[0] = 'No';
        $pago_texto[1] = 'Sí';
        
        $pago_clase[0] = 'alerta';
        $pago_clase[1] = 'exito';
        
    //Mostrar fila
        $clase_fila = 'hidden';
        
    //Array, cant login
        $filtros_evento['t'] = 101;
        $max_login = 5;
        foreach ( $estudiantes->result() as $row_estudiante )
        {
            $filtros_evento['u'] = $row_estudiante->usuario_id;
            $cant_login = $this->Evento_model->cant_eventos($filtros_evento);
            if ( $cant_login > $max_login ) { $max_login = $cant_login; }
            $arr_cant_login[$row_estudiante->usuario_id] = $cant_login;
        }
        
        //Ordenar array, por login, de mayor a menor
        arsort($arr_cant_login);

?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var app_url = '<?php echo base_url() ?>';
    var grupo_id = <?php echo $row->id ?>;

// Document Ready
//-----------------------------------------------------------------------------
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

        $('#estudiantes_form').submit(function(){
            ejecutar_proceso();
            return false;
        });
    });

// FURNCIONES
//-----------------------------------------------------------------------------
    function ejecutar_proceso(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'grupos/ejecutar_proceso/' + grupo_id,
            data: $('#estudiantes_form').serialize(),
            success: function(response){
                if ( response.quan_executed > 0 ) {
                    var message = '<b>' + response.quan_executed + '</b> procesados<br/>Actualizando listado...';
                    toastr['success'](message, response.process);
                } else {
                    toastr['info']('No se procesó ningún estudiante');
                }
                //Recargar página despues de 2500 milsegundos
                setTimeout(() => {
                    window.location = app_url + 'grupos/estudiantes/' + grupo_id;
                }, 2500);
            }
        });
    }
</script>

<?php $this->load->view('grupos/submenu_estudiantes_v') ?>

<form accept-charset="utf-8" method="POST" id="estudiantes_form">

    <?php if ( in_array($this->session->userdata('rol_id'), array(0,1,2,3,5,8)) ) { ?>
        <div class="sep1">
            <div class="row mb-2">
                <div class="col-md-2">
                    <?php echo  form_dropdown('proceso', $opciones_proceso, set_value('proceso'), 'class="form-control"') ?>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-block" type="submit">
                        Aplicar
                    </button>
                </div>
                <div class="col-md-8">
                    A los estudiantes que se seleccionen se les ejecutará el proceso elegido. Al desactivar un usuario también se <span class="resaltar">restaurará su contraseña</span> al valor por defecto.
                </div>
            </div>
        </div>
    <?php } ?>
    

    <table class="table table-default bg-blanco" cellspacing="0">
        <thead>
            <tr>
                <th width="10px"><?php echo form_checkbox($att_check_todos) ?></th>
                <th>Estudiante</th>
                <th>Cantidad login</th>
                <th>Username</th>
                <th class="w3">Pago</th>
                <th class="w3">Estado</th>
                <th>Sexo</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($arr_cant_login as $usuario_id => $cant_login): ?>
                <?php
                    $row_estudiante = $this->Pcrn->registro_id('usuario', $usuario_id);
                
                    $nombre_estudiante = $row_estudiante->apellidos . ' ' . $row_estudiante->nombre;
                        
                    //Activo
                        $valor_activo = '<span class="w3 etiqueta exito">Activo</span>';
                        if ( $row_estudiante->estado == 0 ) { $valor_activo = '<span class="w3 etiqueta alerta">Inactivo</span>'; }
                        
                    //Checkbox
                        $att_check['name'] = $row_estudiante->id;
                        
                    //Mostrar fila
                        $mostrar_estudiante = $this->Usuario_model->mostrar_estudiante($row_estudiante);
                        $clase_fila = $this->Pcrn->si_cero($mostrar_estudiante, 'hidden', '');
                        
                    //Complementar filtros
                        $filtros_evento['u'] = $row_estudiante->id;
                        //$cant_login = $arr_cant_login[$row_estudiante->id];
                        
                        $percent = $this->Pcrn->int_percent($cant_login, $max_login);
                        $clase_barra = 'progress-bar';
                        
                        if ( $percent < 10 )
                        {
                            $percent = 5;
                            $clase_barra = 'progress-bar-danger';
                        }
                ?>

                <tr class="<?php echo $clase_fila ?>">
                    <td><?php echo form_checkbox($att_check) ?></td>
                    <td>
                        <?php echo anchor("usuarios/actividad/{$row_estudiante->id}/1", $nombre_estudiante, 'class="" title=""') ?>
                    </td>
                    
                    <td>
                        <div class="progress">
                            <div class="progress-bar <?php echo $clase_barra ?>" role="progressbar" aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent ?>%;">
                                <?php echo $cant_login ?>
                            </div>
                        </div>
                    </td>
                    
                    <td><?php echo $row_estudiante->username ?></td>
                    
                    <td>
                        <span class="w1 etiqueta <?php echo $pago_clase[$row_estudiante->pago] ?>">
                            <?php echo $pago_texto[$row_estudiante->pago] ?>
                        </span>
                        
                    </td>
                    <td><?php echo $valor_activo ?></td>
                    <td><?php echo $arr_sexo['0' . $row_estudiante->sexo]; ?></td>
                </tr>
            <?php endforeach ?>


        </tbody>
    </table>

</form>