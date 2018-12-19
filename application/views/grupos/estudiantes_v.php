<?php $this->load->view('assets/icheck'); ?>

<?php

    //Variables para construcción del formulario

    $opciones_proceso = array(
        'p1' => 'Activar',
        'p2' => 'Desactivar',
        'p3' => 'Restaurar contraseña',
        'p4' => 'Eliminar',
        'p5' => 'Marcar como pagado',
        'p6' => 'Marcar como NO pagado'
    );
    
    $arr_sexo = $this->Item_model->arr_item(59);
    
    if ( $this->session->userdata('rol_id') > 2 ){
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
    
    //
    
    $att_submit = array(
        'value' =>  'Aplicar',
        'class' => 'btn btn-primary'
    );
            
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
    });
</script>

<?php $this->load->view('grupos/submenu_estudiantes_v') ?>

<?php if ( $this->session->flashdata('resultado') != NULL ):?>
    <?php $resultado = $this->session->flashdata('resultado') ?>
    <div class="alert alert-info"><?= $resultado['proceso'] ?>: Se procesaron <?= $resultado['num_procesados'] ?> usuarios</div>
<?php endif ?>
    
<?= form_open("grupos/ejecutar_proceso/{$row->id}") ?>

<div class="sep2" style="overflow: hidden">
    <div class="casilla">
        <?=  form_dropdown('proceso', $opciones_proceso, set_value('proceso'), 'class="form-control"') ?>
    </div>
    <div class="casilla">
        <?= form_submit($att_submit) ?>
    </div>
    <div class="casilla">
        A los estudiantes que se seleccionen se les ejecutará el proceso elegido. Al desactivar un usuario también se <span class="resaltar">restaurará su contraseña</span> al valor por defecto.
    </div>
</div>

<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th width="10px"><?= form_checkbox($att_check_todos) ?></th>
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

            <tr class="<?= $clase_fila ?>">
                <td><?= form_checkbox($att_check) ?></td>
                <td>
                    <?= anchor("usuarios/actividad/{$row_estudiante->id}/1", $nombre_estudiante, 'class="" title=""') ?>
                </td>
                
                <td>
                    <div class="progress">
                        <div class="progress-bar <?= $clase_barra ?>" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent ?>%;">
                            <?= $cant_login ?>
                        </div>
                    </div>
                </td>
                
                <td><?= $row_estudiante->username ?></td>
                
                <td>
                    <span class="w1 etiqueta <?= $pago_clase[$row_estudiante->pago] ?>">
                        <?= $pago_texto[$row_estudiante->pago] ?>
                    </span>
                    
                </td>
                <td><?php echo $valor_activo ?></td>
                <td><?php echo $arr_sexo['0' . $row_estudiante->sexo]; ?></td>
            </tr>
        <?php endforeach ?>


    </tbody>
</table>

<?= form_close() ?>