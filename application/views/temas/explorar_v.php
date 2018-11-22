<?= $this->load->view('assets/chosen_jquery'); ?>
<?= $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'tema';  //Elemento en singular
    $elemento_p = 'temas'; //Elemento en plural
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
        $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Seleccione el área');
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo', 'Filtrar por Nivel');
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 17', 'Tipo tema');
        
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
        $clases_col['tipo_id'] = 'hidden-xs hidden-sm';
        $clases_col['nivel'] = 'hidden-xs hidden-sm';
        $clases_col['area_id'] = 'hidden-xs hidden-sm';
        $clases_col['cant_quices'] = 'hidden-xs hidden-sm';
        $clases_col['nuevo_quiz'] = 'hidden-xs hidden-sm';
        $clases_col['botones'] = 'hidden-xs hidden-sm';
        
?>

<?php
    //Tipos de tema
        $arr_tipos = $this->Item_model->arr_interno('categoria_id = 17');
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
            url: base_url + 'temas/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'temas/explorar/?' + busqueda_str;
            }
        });
    }
</script>

<?php $this->load->view($vista_menu) ?>

<div class="row">
    <div class="col-md-6 sep1">
        <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
        <?= form_input($att_q) ?>
        <?= form_dropdown('a', $opciones_area, $busqueda['a'], 'class="form-control chosen-select" title="Filtrar por área"'); ?>
        <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'class="form-control chosen-select" title="Filtrar por nivel"'); ?>
        <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control chosen-select" title="Filtrar por tipo de tema"'); ?>
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
                <?= anchor("temas/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
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
                <th width="100px">Cód. tema</th>

                <th class="<?= $clases_col['tema'] ?>">Temas</th>
                <th class="<?= $clases_col['tipo_id'] ?>">Tipo</th>
                <th width="60px" class="<?= $clases_col['nivel'] ?>">Nivel</th>
                <th class="<?= $clases_col['area_id'] ?>">Área</th>
                <th width="60px" class="<?= $clases_col['cant_quices'] ?>">Quices</th>

                <th width="35px" class="<?= $clases_col['botones'] ?>"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
                <?php
                    //Variables
                        $nombre_elemento = "{$row_resultado->nombre_tema}";
                        $link_elemento = anchor("temas/index/{$row_resultado->id}", $nombre_elemento);

                    //Checkbox
                        $att_check['data-id'] = $row_resultado->id;
                        
                    //Otros datos
                        $cant_quices = $this->Tema_model->quices($row_resultado->id)->num_rows();
                        $clase_cant = '';
                        if ( $cant_quices > 0 ) { $clase_cant = 'label label-info'; }
                        
                        $clase_tipo = '';
                        if ( $row_resultado->tipo_id ) { $clase_tipo = 'info'; }

                ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>

                    <td class="warning text-right"><?= $row_resultado->id ?></td>
                    <td><?= $row_resultado->cod_tema ?></td>

                    <td>
                        <?= $link_elemento ?>
                    </td>
                    
                    <td class="<?= $clases_col['tipo_id'] ?> <?= $clase_tipo ?>">
                        <?= $arr_tipos[$row_resultado->tipo_id] ?>
                    </td>
                    
                    <td class="<?= $clases_col['nivel'] ?>">
                        <span class="etiqueta nivel w2"><?= $arr_nivel[$row_resultado->nivel] ?></span>
                    </td>
                    
                    <td>
                        <?= $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                    </td>
                    
                    <td class="<?= $clases_col['cant_quices'] ?>">
                        <span class="<?= $clase_cant ?>"><?= $cant_quices ?></span>
                    </td>

                    <td class="<?= $clases_col['botones'] ?>">
                        <?= anchor("temas/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>

<?= $this->load->view('app/modal_eliminar'); ?>