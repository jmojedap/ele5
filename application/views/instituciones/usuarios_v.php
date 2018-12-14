<?php $this->load->view('assets/icheck'); ?>

<?php

    //Variables para construcción del formulario
        $opciones_proceso = array(
            'p1' => 'Activar',
            'p2' => 'Desactivar',
            'p3' => 'Restaurar contraseña',
            'p4' => 'Eliminar'
        );

        if ( $this->session->userdata('rol_id') == 3 ){
            $opciones_proceso = array(
                'p3' => 'Restaurar contraseña'
            );
        } elseif ( $this->session->userdata('rol_id') > 3 ){
            $opciones_proceso = array(
                'p3' => 'Restaurar contraseña'
            );
        }

        $att_submit = array(
            'value' =>  'Aplicar',
            'class' => 'btn btn-primary'
        );
        
    //Condiciones cant login
        $condiciones[0] = 'tipo_evento_id = 1'; //Login
        
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro',
            'checked' => FALSE,
            'value' => 1
        );
        
    //Array, cant login
        $filtros_evento['t'] = 101;
        $max_login = 5;
        foreach ( $usuarios->result() as $row_usuario ) 
        {
            $filtros_evento['u'] = $row_usuario->usuario_id;
            $cant_login = $this->Evento_model->cant_eventos($filtros_evento);
            if ( $cant_login > $max_login ) { $max_login = $cant_login; }
            $arr_cant_login[$row_usuario->usuario_id] = $cant_login;
        }
        
        //Ordenar array, por login, de mayor a menor
        arsort($arr_cant_login);
        
    //Clases columnas
        $clases_col['rol'] = 'hidden-xs hidden-sm';
        $clases_col['sexo'] = 'hidden-xs hidden-sm';
        $clases_col['email'] = 'hidden-xs hidden-sm';
        $clases_col['estado'] = 'hidden-xs hidden-sm';
        $clases_col['grupos'] = 'hidden-xs hidden-sm';
        

?>

<script>
    $(document).ready(function()
    {
        
        $('#check_todos').on('ifChanged', function(){
            
            if( $(this).is(":checked") ) {
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

<?php $this->load->view('instituciones/submenu_usuarios_v') ?>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<?php echo form_open("instituciones/procesar_usuarios/{$row->id}") ?>
    
<?php if ( in_array($this->session->userdata('rol_id'), array(0,1,2,3,8))  ) : ?> 
    <div class="row">
        <div class="col col-md-2">
            <?php echo  form_dropdown('proceso', $opciones_proceso, set_value('proceso'), 'class="form-control select-chosen"') ?><br/>
        </div>
        <div class="col col-md-2">
            <?php echo form_submit($att_submit) ?> 
        </div>

        <div class="col col-md-8">
            <p class="p1">
                A los usuarios que se seleccionen se les ejecutará el proceso elegido. Al desactivar un usuario también se <span class="resaltar">restaurará su contraseña</span> al valor por defecto.
            </p>
        </div>
    </div>   
<?php endif ?>

<table class="table table-hover bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th width="10px;"><?php echo form_checkbox($att_check_todos) ?></th>
            <?php if ( $this->session->userdata('rol_id') < 2 ){ ?>
                <th width="70px">ID</th>
            <?php } ?>
            <th>Nombre usuario</th>
            <th>Cantidad login</th>
            <th class="<?php echo $clases_col['rol'] ?>">Rol</th>
            <th class="<?php echo $clases_col['sexo'] ?>">Sexo</th>
            <th class="<?php echo $clases_col['email'] ?>">E-mail</th>
            <th class="<?php echo $clases_col['estado'] ?>">Estado</th>
            <th class="<?php echo $clases_col['grupos'] ?>">Grupos</th>
            
        </tr>
    </thead>
    <tbody>

        <?php foreach ($arr_cant_login as $usuario_id => $cant_login): ?>
            <?php
                $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
            
                //Activo
                    $valor_activo = '<span class="w3 etiqueta exito">Activo</span>';
                    if ( $row_usuario->estado == 0 ) { $valor_activo = '<span class="w3 etiqueta alerta">Inactivo</span>'; }
                
                $grupos = $this->Grupo_model->grupos_profesor($row_usuario->id);
                    
                //Checkbox
                    $att_check['name'] = $row_usuario->id;
                    $att_check['data-id'] = $row_resultado->id;
                    
                //Complementar filtros
                    $filtros_evento['u'] = $row_usuario->id;
                    $cant_login = $arr_cant_login[$row_usuario->id];
                    
                    $percent = $this->Pcrn->int_percent($cant_login, $max_login);
                    $clase_barra = 'progress-bar';
                    
                    if ( $percent < 20 )
                    {
                        $percent = 5;
                        $clase_barra = 'progress-bar-danger';
                    }
            ?>

            <tr>
                <td><?php echo form_checkbox($att_check) ?></td>
                <?php if ( $this->session->userdata('rol_id') < 2 ){ ?>
                    <td class="warning text-right"><?php echo $row_usuario->id ?></td>
                <?php } ?>
                <td>
                    <?php echo anchor("usuarios/actividad/{$row_usuario->id}/1", $this->App_model->nombre_usuario($row_usuario->id, 3), 'class="" title=""') ?>
                    <br/>
                    <?php echo $row_usuario->username ?>
                </td>
                <td>
                    <div class="progress">
                        <div class="progress-bar <?php echo $clase_barra ?>" role="progressbar" aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent ?>%; min-width: 10px;">
                            <?php echo $cant_login ?>
                        </div>
                    </div>
                </td>
                <td class="<?php echo $clases_col['rol'] ?>"><?php echo $this->Item_model->nombre(58, $row_usuario->rol_id) ?></td>
                <td class="<?php echo $clases_col['sexo'] ?>"><?php echo $this->Item_model->nombre(59, $row_usuario->sexo); ?></td>
                <td class="<?php echo $clases_col['email'] ?>"><?php echo $row_usuario->email ?></td>
                <td class="<?php echo $clases_col['estado'] ?>"><?php echo $valor_activo ?></td>
                <td class="<?php echo $clases_col['grupos'] ?>">
                    <?php foreach ($grupos->result() as $row_grupo) : ?>
                        <?php echo anchor("grupos/estudiantes/{$row_grupo->id}", $this->App_model->nombre_grupo($row_grupo->id), 'class="a2" title""') ?>
                    <?php endforeach ?>
                </td>
                
            </tr>
        <?php endforeach ?>


    </tbody>
</table>

<?php echo form_close() ?>