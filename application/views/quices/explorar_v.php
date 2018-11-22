<?= $this->load->view('assets/chosen_jquery'); ?>
<?= $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'quiz';  //Elemento en singular
    $elemento_p = 'quices'; //Elemento en plural
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
        $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Área');
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 9', 'Tipo evidencia');
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo', 'Nivel');
        
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
        $clases_col['nivel'] = 'hidden-xs hidden-sm';
        $clases_col['area'] = 'hidden-xs hidden-sm';
        $clases_col['cant_elementos'] = 'hidden-xs hidden-sm';
        $clases_col['vista_previa'] = 'hidden-xs';
        $clases_col['tipo'] = 'hidden-xs hidden-sm';
        $clases_col['usuario'] = 'hidden-xs hidden-sm';
        $clases_col['editado'] = 'hidden-xs hidden-sm';
        $clases_col['botones'] = 'hidden-xs hidden-sm';
        
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
            url: base_url + 'quices/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'quices/explorar/?' + busqueda_str;
            }
        });
    }
</script>

<?php $this->load->view($vista_menu) ?>

<div class="row">
    <div class="col-md-8 sep1">
        <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
        <?= form_input($att_q) ?>
        <?= form_dropdown('a', $opciones_area, $busqueda['a'], 'class="form-control chosen-select" title="Filtrar por área"'); ?>
        <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'class="form-control chosen-select" title="Filtrar por nivel"'); ?>
        <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control chosen-select" title="Filtrar por tipo"'); ?>
        
        <?= form_submit($att_submit) ?>
        <?= form_close() ?>
    </div>

    <div class="col-md-2 sep1">
        <div class="btn-toolbar" role="toolbar" aria-label="...">
            <div class="btn-group" role="group" aria-label="...">
                <a class="btn btn-warning" title="Eliminar los <?= $elemento_s ?> seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>

            <div class="btn-group hidden-xs" role="group">
                <?= anchor("quices/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-xs-6 sep1">
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

                <th class="<?= $clases_col['quiz'] ?>">Quiz</th>
                
                <th class="<?= $clases_col['nivel_area'] ?>">Nivel - Área</th>
                <th class="<?= $clases_col['cant_elementos'] ?>">Cant Elementos</th>
                <th class="<?= $clases_col['vista_previa'] ?>">Vista previa</th>
                <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
                <th class="<?= $clases_col['usuario'] ?>">Usuario</th>
                <th class="<?= $clases_col['editado'] ?>">Editado</th>

                <th width="35px" class="<?= $clases_col['botones'] ?>"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
                <?php
                    //Variables
                        $nombre_elemento = "{$row_resultado->nombre_quiz}";
                        $link_elemento = anchor("quices/index/$row_resultado->id", $nombre_elemento);
                        $cant_elementos = $this->Pcrn->num_registros('quiz_elemento', "quiz_id = {$row_resultado->id}");

                    //Checkbox
                        $att_check['data-id'] = $row_resultado->id;
                        
                    

                ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>

                    <td class="warning text-right"><?= $row_resultado->id ?></td>

                    <td>
                        <?= $link_elemento ?>
                    </td>
                    
                    
                    <td class="<?= $clases_col['area'] ?>">
                        <span class="etiqueta nivel w1"><?= $row_resultado->nivel ?></span>
                        <?= $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                    </td>
                    
                    <td class="<?= $clases_col['cant_elementos'] ?>">
                        <?= $cant_elementos ?>
                    </td>
                    
                    <td class="<?= $clases_col['vista_previa'] ?>">
                        <?= anchor("quices/resolver/$row_resultado->id", '<i class="fa fa-external-link"></i>', 'target="_blank" class="btn btn-info btn-sm"') ?>
                    </td>
                    
                    <td class="<?= $clases_col['tipo'] ?>">
                        <?= $this->Item_model->nombre(9, $row_resultado->tipo_quiz_id) . $row_resultado->tipo_quiz_id ?>
                    </td>
                    
                    <td class="<?= $clases_col['usuario'] ?>">
                        <?= $this->App_model->nombre_usuario($row_resultado->usuario_id) ?>
                    </td>
                    
                    <td class="<?= $clases_col['editado'] ?>">
                        <?= $this->Pcrn->fecha_formato($row_resultado->editado, 'M-d') ?>
                        <span class="suave">
                            (Hace <?= $this->Pcrn->tiempo_hace($row_resultado->editado) ?>)
                        </span>
                    </td>

                    <td class="<?= $clases_col['botones'] ?>">
                        <?= anchor("quices/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>

<?= $this->load->view('app/modal_eliminar'); ?>