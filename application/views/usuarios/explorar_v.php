<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'Usuario';  //Elemento en singular
    $elemento_p = 'Usuarios'; //Elemento en plural
    $controlador = $this->uri->segment(1);

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-primary',
            'value' =>  'Buscar'
        );
        
        //Opciones de dropdowns
        $opciones_rol = $this->Item_model->opciones('categoria_id = 6', 'Rol de usuario');
        $opciones_institucion = $this->Pcrn->opciones_dropdown($instituciones, 'id', 'nombre_institucion', $texto_vacio = ' Institución ');
        
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro',
            'checked' => FALSE
        );
        
        $seleccionados_todos = '';
        foreach ( $resultados->result() as $row_resultado ) {
            $seleccionados_todos .= '-' . $row_resultado->id;
        }
        
    //Clases columnas
        $clases_col['institucion'] = 'hidden-xs';
        $clases_col['rol'] = 'hidden-xs';
        $clases_col['estado'] = 'hidden-xs hidden-sm';
        $clases_col['pago'] = 'hidden-xs hidden-sm';
        $clases_col['restaurar'] = 'hidden-xs hidden-sm';
?>

<script>
//Variables
        var base_url = '<?= base_url() ?>';
        var busqueda_str = '<?= $busqueda_str ?>';
        var seleccionados = '';
        var seleccionados_todos = '<?= $seleccionados_todos ?>';
        var registro_id = 0;
        
        var usuario_id = 0;
        var estado = 0;

//DOCUMENT READY

    $(document).ready(function(){
        
        $('.check_registro').on('ifChanged', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked"))
            {
                //Activado
                $('.check_registro').iCheck('check');
                seleccionados = seleccionados_todos;
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
                seleccionados = '';
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
        
        //Específicas USUARIOS
        $('.chosen-drop').css({"width": "500px"});
        
        //Ajuste de formato
        $('.chosen-container').css('width', '150');
        //alert($('.chosen-container').css('width'));
        
        //Botón, se alterna el valor del campo usuario.activo
        $('.alternar_activacion').click(function(){
            usuario_id = $(this).data('usuario_id');
            alternar_activacion();
        });
        
        //Botón, se alterna el valor del campo usuario.pago
        $('.alternar_pago').click(function(){
            usuario_id = $(this).data('usuario_id');
            alternar_pago();
        });
        
        $('.restaurar_contrasena').click(function(){
            usuario_id = $(this).data('usuario_id');
            restaurar_contrasena();
        });
    });

//FUNCIONES

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'usuarios/explorar/?' + busqueda_str;
            }
        });
    }
    
    //Específicas USUARIOS
    //
    //Cambia el valor del campo usuario.activo
    function alternar_activacion()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/alternar_activacion/' + usuario_id,
            success: function(respuesta){
                estado = respuesta;
                alt_elementos_activacion();
            }
        });
    }
    
    //Ajax, cambia el valor del campo usuario.pago
    function alternar_pago(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/alternar_pago/' + usuario_id,
            success: function(resultados){
                alt_elementos_pago();
                estado = resultados.estado;
                alt_elementos_activacion();
            }
        });
    }
    
    //Ajax, restaurar la contraseña al valor por defecto
    function restaurar_contrasena()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/restaurar_contrasena/' + usuario_id,
            success: function(){
                $('#restaurar_' + usuario_id).addClass('hidden');
                $('#restaurada_' + usuario_id).removeClass('hidden');
            }
        });
    }
    
    //Función que muestra u oculta los botones dependiendo del estado usuario.activo
    function alt_elementos_activacion()
    {
        if ( estado == 0 ) {
            $('#inactivo_' + usuario_id).addClass('hidden');
            $('#activo_' + usuario_id).removeClass('hidden');
        } else {
            $('#inactivo_' + usuario_id).removeClass('hidden');
            $('#activo_' + usuario_id).addClass('hidden');
        }
    }
    
    function alt_elementos_pago()
    {
        var elementos = '.pago_' + usuario_id;
        $(elementos).toggleClass('hidden');
    }
</script>

<div class="">
    <div class="row">
        <div class="col-md-6 sep2">
            <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
            <?= form_input($att_q) ?>
            <?= form_dropdown('rol', $opciones_rol, $busqueda['rol'], 'class="form-control" title="Filtrar por rol de usuario"'); ?>
            <?= form_dropdown('i', $opciones_institucion, $busqueda['i'], 'class="form-control chosen-select" title="Filtrar por tipo de institución" style="width: 260px;"'); ?>
            <?= form_submit($att_submit) ?>
            <?= form_close() ?>
        </div>
        
        <div class="col-md-3 col-xs-6 sep2">
            <div class="btn-toolbar" role="toolbar" aria-label="...">
                <div class="btn-group" role="group" aria-label="...">
                    <a class="btn btn-warning" title="Eliminar los elementos seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </div>

                <div class="btn-group hidden-xs" role="group">
                    <?= anchor("usuarios/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-xs-6 sep2">
            <div class="pull-right">
                <p id="seleccionados"></p>
                <?= $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>
</div>

<div class="bs-caja-no-padding">
    <table class="table table-responsive table-hover" cellspacing="0">
        <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="50px;">ID</th>
                <th class="<?= $clases_col['login'] ?>"></th>
                <th><?= $elemento_s ?></th>
                
                <th class="<?= $clases_col['institucion'] ?>">Institución</th>
                <th class="<?= $clases_col['rol'] ?>">Rol</th>
                <th class="<?= $clases_col['estado'] ?>">Estado</th>
                <th class="<?= $clases_col['pago'] ?>">Pago</th>
                <th class="<?= $clases_col['restaurar'] ?>">Contraseña</th>
                
                <th width="35px" class="hidden-xs"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = "{$row_resultado->nombre} {$row_resultado->apellidos}";
                    $link_elemento = anchor("usuarios/actividad/$row_resultado->id", $nombre_elemento);
                    $editable = $this->Usuario_model->editable($row_resultado);
                    
                //Activación
                    $clase_activacion = 'activacion_' . $row_resultado->id;
                    $clase_desactivar = $this->Pcrn->si_cero($row_resultado->estado, 'hidden', '');        //Está activo
                    $clase_activar = $this->Pcrn->si_cero($row_resultado->estado, '', 'hidden');    //Está activo
                        
                //Pagos
                    $clase_pago = 'pago_' . $row_resultado->id;
                    $clase_pago_si = $this->Pcrn->si_cero($row_resultado->pago, '', 'hidden');    //Sin pagar
                    $clase_pago_no = $this->Pcrn->si_cero($row_resultado->pago, 'hidden', '');    //Pagado

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;

            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    
                    <td class="warning"><?= $row_resultado->id ?></td>
                    
                    <td class="<?= $clases_col['login'] ?>">
                        <?= anchor("develop/ml/{$row_resultado->id}", '<div class="a4 w2"><i class="fa fa-sign-in"></i></div>', 'class="" title="Ingresar a la plataforma con este usuario"') ?>
                    </td>
                    
                    <td>
                        <?= $link_elemento ?>
                        <br/>
                        <?= $row_resultado->username ?>
                    </td>
                    
                    <td class="<?= $clases_col['institucion'] ?>">
                        <span class="suave">Institución</span>
                        <?= $this->App_model->nombre_institucion($row_resultado->institucion_id) ?>

                        <br/>

                        <span class="suave">Creado</span>
                        <?= $this->Pcrn->fecha_formato($row_resultado->creado, 'Y-M-d') ?>

                        <span class="suave">por</span>
                        <?= $this->App_model->nombre_usuario($row_resultado->creado_usuario_id) ?>
                    </td>
                    
                    <td class="<?= $clases_col['rol'] ?>"><?= $this->Item_model->nombre(6, $row_resultado->rol_id); ?></td>
                    
                    <td class="<?= $clases_col['estado'] ?>">
                        <span id="activo_<?= $row_resultado->id ?>" class="w3 btn btn-warning alternar_activacion small <?= $clase_activar ?> <?= $clase_activacion ?>" data-usuario_id="<?= $row_resultado->id ?>" title="Activar">
                            <i class="fa fa-circle-o"></i> Inactivo
                        </span>

                        <span id="inactivo_<?= $row_resultado->id ?>" class="w3 btn btn-success alternar_activacion small <?= $clase_desactivar ?> <?= $clase_activacion ?>" data-usuario_id="<?= $row_resultado->id ?>" title="Activar">
                            <i class="fa fa-check-circle-o"></i> Activo
                        </span>
                    </td>
                    
                    <td class="<?= $clases_col['pago'] ?>">
                        <span class="w2 btn btn-warning alternar_pago small <?= $clase_pago_si ?> <?= $clase_pago ?>" data-usuario_id="<?= $row_resultado->id ?>" title="">
                            <i class="fa fa-circle-o"></i> No
                        </span>

                        <?php if ( $this->session->userdata('rol_id') <= 2 ){ ?>
                            <span class="w2 btn btn-success alternar_pago small <?= $clase_pago_no ?> <?= $clase_pago ?>" data-usuario_id="<?= $row_resultado->id ?>" title="">
                                <i class="fa fa-check-circle-o"></i> Sí
                            </span>
                        <?php } else { ?>
                            <span class="etiqueta exito w1 <?= $clase_pago_no ?> <?= $clase_pago ?>">
                                Sí
                            </span>
                        <?php } ?>
                    </td>
                    
                    <td class="<?= $clases_col['restaurar'] ?>">
                        <div class="btn btn-default small restaurar_contrasena" id="restaurar_<?= $row_resultado->id ?>" data-usuario_id="<?= $row_resultado->id ?>" title="Restaurar la contraseña al valor por defecto">
                            Restaurar
                        </div>
                        <span class="etiqueta exito hidden" id="restaurada_<?= $row_resultado->id ?>">
                            Restaurada
                        </span>
                    </td>
                    
                    <td class="hidden-xs">
                        <?php if ( $editable ){ ?>
                            <?= anchor("usuarios/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>    
</div>

<?= $this->load->view('app/modal_eliminar'); ?>