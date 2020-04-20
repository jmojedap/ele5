<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'institución';  //Elemento en singular
    $elemento_p = 'instituciones'; //Elemento en plural
    $funcion_controlador = $this->uri->segment(2);

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
        //$opciones_item = $this->Item_model->opciones('categoria_id = 2', 'Seleccione el item');
        
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
        <?= form_open("datos/ayudas_redirect/{$funcion_controlador}", $att_form) ?>
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

                <th class="<?= $clases_col[''] ?>">Título</th>
                <th class="<?= $clases_col['abreviatura'] ?>">ID Post</th>
                <th class="<?= $clases_col['roles'] ?>">Roles</th>

                <th width="35px" class="<?= $clases_col['botones'] ?>"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
                <?php
                    //Variables
                        $nombre_elemento = "{$row_resultado->item_largo}";
                        $link_elemento = anchor("https://www.plataformaenlinea.com/ayuda/?p={$row_resultado->abreviatura}", $nombre_elemento, 'target="_blank"');

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
                    <td class="<?= $clases_col['abreviatura'] ?>">
                        <?= $row_resultado->abreviatura ?>
                    </td>
                    <td class="<?= $clases_col['roles'] ?>">
                        <?= $row_resultado->item_corto ?>
                    </td>

                    <td class="<?= $clases_col['botones'] ?>">
                        <?= anchor("datos/ayudas_editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>

<?php $this->load->view('app/modal_eliminar'); ?>