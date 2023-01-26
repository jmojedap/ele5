<?php //$this->load->view('assets/icheck'); ?>

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
        foreach ( $usuarios->result() as $rUser ) 
        {
            $filtros_evento['u'] = $rUser->usuario_id;
            $cant_login = $this->Evento_model->cant_eventos($filtros_evento);
            if ( $cant_login > $max_login ) { $max_login = $cant_login; }
            $arr_cant_login[$rUser->usuario_id] = $cant_login;
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
        $("#check_todos").change(function(){
            $(".check_registro").prop("checked", $(this).prop("checked"));
        });
    });
</script>

<?php $this->load->view('comunes/bs4/resultado_proceso_v'); ?>

<?= form_open("instituciones/procesar_usuarios/{$row->id}") ?>
    
<?php if ( in_array($this->session->userdata('rol_id'), array(0,1,2,3,8))  ) : ?> 
    <div class="row mb-2">
        <div class="col col-md-2">
            <?=  form_dropdown('proceso', $opciones_proceso, set_value('proceso'), 'class="form-control select-chosen"') ?><br/>
        </div>
        <div class="col col-md-2">
            <?= form_submit($att_submit) ?> 
        </div>

        <div class="col col-md-8">
            <p class="p1">
                A los usuarios que se seleccionen se les ejecutará el proceso elegido. Al desactivar un usuario también se <span class="resaltar">restaurará su contraseña</span> al valor por defecto.
            </p>
        </div>
    </div>   
<?php endif ?>

<table class="table table-hover bg-white" cellspacing="0">
    <thead>
        <tr>
            <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
            <?php if ( $this->session->userdata('rol_id') < 2 ){ ?>
                <th width="70px">ID</th>
            <?php } ?>
            <th>Nombre usuario</th>
            <th>Cantidad login</th>
            <th class="<?= $clases_col['rol'] ?>">Rol</th>
            <th class="<?= $clases_col['sexo'] ?>">Sexo</th>
            <th class="<?= $clases_col['email'] ?>">E-mail</th>
            <th class="<?= $clases_col['estado'] ?>">Estado</th>
            <th class="<?= $clases_col['grupos'] ?>">Grupos</th>
            
        </tr>
    </thead>
    <tbody>

        <?php foreach ($arr_cant_login as $usuario_id => $cant_login): ?>
            <?php
                $rUser = $this->Db_model->row_id('usuario', $usuario_id);
            
                //Activo
                    $valor_activo = '<span class="w3 etiqueta exito">Activo</span>';
                    if ( $rUser->estado == 0 ) { $valor_activo = '<span class="w3 etiqueta alerta">Inactivo</span>'; }
                
                $grupos = $this->Grupo_model->grupos_profesor($usuario_id);
                    
                //Checkbox
                    $att_check['name'] = $rUser->id;
                    $att_check['data-id'] = $row_resultado->id;
                    
                //Complementar filtros
                    $filtros_evento['u'] = $rUser->id;
                    $cant_login = $arr_cant_login[$rUser->id];
                    
                    $percent = $this->Pcrn->int_percent($cant_login, $max_login);
                    $clase_barra = 'progress-bar';
                    
                    if ( $percent < 20 )
                    {
                        $percent = 5;
                        $clase_barra = 'progress-bar-danger';
                    }
            ?>

            <tr>
                <td><?= form_checkbox($att_check) ?></td>
                <?php if ( $this->session->userdata('rol_id') < 2 ){ ?>
                    <td class="warning text-right"><?= $rUser->id ?></td>
                <?php } ?>
                <td>
                    <?= anchor("usuarios/actividad/{$rUser->id}/1", $this->App_model->nombre_usuario($rUser->id, 3), 'class="" title=""') ?>
                    <br/>
                    <?= $rUser->username ?>
                </td>
                <td>
                    <div class="progress">
                        <div class="progress-bar <?= $clase_barra ?>" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent ?>%; min-width: 10px;">
                            <?= $cant_login ?>
                        </div>
                    </div>
                </td>
                <td class="<?= $clases_col['rol'] ?>"><?= $this->Item_model->nombre(58, $rUser->rol_id) ?></td>
                <td class="<?= $clases_col['sexo'] ?>"><?= $this->Item_model->nombre(59, $rUser->sexo); ?></td>
                <td class="<?= $clases_col['email'] ?>"><?= $rUser->email ?></td>
                <td class="<?= $clases_col['estado'] ?>"><?= $valor_activo ?></td>
                <td class="<?= $clases_col['grupos'] ?>">
                    <?php foreach ($grupos->result() as $row_grupo) : ?>
                        <a href="<?= URL_APP . "grupos/estudiantes/{$row_grupo->id}" ?>" class="btn btn-sm btn-light">
                            <?= $this->App_model->nombre_grupo($row_grupo->id) ?>
                        </a>
                    <?php endforeach ?>
                </td>
                
            </tr>
        <?php endforeach ?>


    </tbody>
</table>

<?= form_close() ?>