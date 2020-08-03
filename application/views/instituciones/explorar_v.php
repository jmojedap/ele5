<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'institución';  //Elemento en singular
    $elemento_p = 'instituciones'; //Elemento en plural
    $controlador = $this->uri->segment(1);

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'autofocus' => TRUE,
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-primary',
            'value' =>  'Buscar'
        );
        
        //Opciones de dropdowns
        //$opciones_item = $this->App_model->opciones_item(2, 'Seleccione el item');
        
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
        $clases_col['botones'] = 'hidden-xs hidden-sm';
        $clases_col['informacion'] = 'hidden-xs hidden-sm';
        $clases_col['ejecutivo'] = 'hidden-xs hidden-sm';
        
?>

<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var busqueda_str = '<?= $busqueda_str ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?= $seleccionados_todos ?>';
    var registro_id = 0;
        
// Document Ready
//-----------------------------------------------------------------------------

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
            
            if($(this).is(":checked")) { 
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
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'instituciones/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'instituciones/explorar/?' + busqueda_str;
            }
        });
    }
</script>

<?php $this->load->view($vista_menu) ?>

<div class="row">
    <div class="col-md-6 sep1">
        <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
        <?= form_input($att_q) ?>
        <?= form_submit($att_submit) ?>
        <?= form_close() ?>
    </div>

    <div class="col-md-3 col-xs-6 sep1">
        <div class="btn-toolbar" role="toolbar" aria-label="...">
            <div class="btn-group" role="group" aria-label="...">
                <a class="btn btn-warning" title="Eliminar los <?= $elemento_s ?> seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>

            <div class="btn-group hidden-xs" role="group">
                <?= anchor("instituciones/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xs-6 sep1">
        <div class="pull-right">
            <p id="seleccionados"></p>
            <?= $this->pagination->create_links(); ?>
        </div>
    </div>
</div>

<div class="bs-caja-no-padding">
    <table class="table table-hover" cellspacing="0">
        <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="60px;">ID</th>
                <th width="10px;">Cód</th>

                <th class="<?= $clases_col['institucion'] ?>">Institución</th>
                <th class="<?= $clases_col['informacion'] ?>">Información</th>
                <th class="<?= $clases_col['ejecutivo'] ?>">Ejecutivo</th>

                <th width="35px" class="<?= $clases_col['botones'] ?>"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
                <?php
                    //Variables
                        $nombre_elemento = "{$row_resultado->nombre_institucion}";
                        $link_elemento = anchor("instituciones/index/$row_resultado->id", $nombre_elemento);

                    //Checkbox
                        $att_check['data-id'] = $row_resultado->id;
                        
                    /*$cant_login = $this->Institucion_model->cant_login($row_resultado->id);
                    $cant_estudiantes = $this->Institucion_model->cant_estudiantes($row_resultado->id);
                    $cant_pagaron = $this->Institucion_model->cant_estudiantes($row_resultado->id, 'pago = 1');

                    $porcentaje_pagaron = 0; 
                    if ( $cant_estudiantes > 0 ) { $porcentaje_pagaron = 100 * $cant_pagaron / $cant_estudiantes; }

                    $promedio_login = 0;
                    if ( $cant_login > 0 ) { $promedio_login = $cant_login / $cant_estudiantes; } */

                    $cant_login = 0;
                    $cant_estudiantes = 0;
                    $cant_pagaron = 0;

                    $porcentaje_pagaron = 0; 
                    //if ( $cant_estudiantes > 0 ) { $porcentaje_pagaron = 100 * $cant_pagaron / $cant_estudiantes; }

                    $promedio_login = 0;
                    //if ( $cant_login > 0 ) { $promedio_login = $cant_login / $cant_estudiantes; }

                ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>


                    <td class="warning text-right"><?= $row_resultado->id ?></td>
                    <td><?= $row_resultado->cod ?></td>

                    <td>
                        <?= $link_elemento ?>
                        <br/>
                        <span class="suave"><i class="fa fa-users"></i></span>
                        <span class="resaltar"><?= $cant_estudiantes ?></span>
                        <span class="suave"> | </span>

                        <span class="suave">Login</span>
                        <span class="resaltar"><?= $cant_login ?></span>
                        <span class="suave"> | </span>

                        <span class="suave">Promedio</span>
                        <span class="resaltar"><?= number_format($promedio_login, 1) ?></span>
                        <span class="suave"> | </span>

                        <span class="suave">Pagaron</span>
                        <span class="resaltar"><?= $cant_pagaron ?></span>
                        <span class="suave">(<?= number_format($porcentaje_pagaron, 0) ?>%)</span>
                        <span class="suave"> | </span>
                    </td>
                    
                    <td class="<?= $clases_col['informacion'] ?>">
                        <?= $this->App_model->nombre_lugar($row_resultado->lugar_id, 'CR') ?>
                    
                        <?php if ( strlen($row_resultado->direccion) > 0 OR strlen($row_resultado->telefono) > 0 ) : ?>                
                            <br/>
                            <span class="resaltar"><i class="fa fa-phone-square"></i></span>
                            <span class="suave"><?= $row_resultado->telefono ?></span>

                            <span class="resaltar"><i class="fa fa-home"></i></span>
                            <span class="suave"><?= $row_resultado->direccion ?></span>
                        <?php endif ?>

                            <span class="suave"> | </span>
                            <span class="suave">Acumulador: </span>
                            <span class="resaltar"><?= $row_resultado->acumulador ?></span>

                            <br/>

                            <span class="suave">Vencimiento cartera: </span>
                            <span class="resaltar"><?= $this->Pcrn->fecha_formato($row_resultado->vencimiento_cartera, 'd-M') ?></span>
                            <span class="suave"> | <?= $this->Pcrn->tiempo_hace($row_resultado->vencimiento_cartera, TRUE) ?></span>

                        <?php if ( strlen($row_resultado->pagina_web) > 0 ){ ?>
                            <br/>
                            <?= anchor($row_resultado->pagina_web, str_replace('http://', '', $row_resultado->pagina_web), 'class="" target="_blank"') ?>
                        <?php } ?>
                    </td>
                    
                    <td class="<?= $clases_col['ejecutivo'] ?>">
                        <?= $this->App_model->nombre_usuario($row_resultado->ejecutivo_id, 2) ?>
                    </td>

                    <td class="<?= $clases_col['botones'] ?>">
                        <?= anchor("instituciones/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>

<?php $this->load->view('app/modal_eliminar'); ?>