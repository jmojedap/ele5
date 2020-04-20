<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'kit';  //Elemento en singular
    $elemento_p = 'kits'; //Elemento en plural
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
        $clases_col['instituciones'] = 'hidden-xs hidden-sm';
        $clases_col['flipbooks'] = 'hidden-xs hidden-sm';
        $clases_col['cuestionarios'] = 'hidden-xs hidden-sm';
        $clases_col['descripcion'] = 'hidden-xs hidden-sm';
        $clases_col['editado'] = 'hidden-xs hidden-sm';
?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var busqueda_str = '<?= $busqueda_str ?>';
        var seleccionados = '';
        var seleccionados_todos = '<?= $seleccionados_todos ?>';
        var registro_id = 0;
        
        var kit_id = 0;
        var inactivo = 0;
        
// Document
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
            url: base_url + 'kits/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'kits/explorar/?' + busqueda_str;
            }
        });
    }
    
    
</script>

<?php $this->load->view($vista_menu) ?>

<div class="">
    <div class="row">
        <div class="col-md-6 sep2">
            <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
            <?= form_input($att_q) ?>
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
                    <?= anchor("kits/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
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
                <th><?= ucfirst($elemento_s) ?></th>
                
                
                
                <th class="<?= $clases_col['instituciones'] ?>">Instituciones</th>
                <th class="<?= $clases_col['flipbooks'] ?>">Contenidos</th>
                <th class="<?= $clases_col['cuestionarios'] ?>">Cuestionarios</th>
                <th class="<?= $clases_col['descripcion'] ?>">Descripción</th>
                <th class="<?= $clases_col['editado'] ?>">Editado</th>
                
                <th width="35px" class="hidden-xs"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = $row_resultado->nombre_kit;
                    $link_elemento = anchor("kits/flipbooks/$row_resultado->id", $nombre_elemento);
                    $editable = $this->Kit_model->editable($row_resultado->id);
                    $row_plus = $this->Kit_model->row_plus($row_resultado->id);

                //Checkbox
                    $att_check['data-id'] = $row_resultado->id;

            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    
                    <td class="warning"><?= $row_resultado->id ?></td>
                    
                    <td>
                        <?= $link_elemento ?>
                    </td>
                    
                    <td class="<?= $clases_col['instituciones'] ?>">
                        <?= $row_plus->cant_instituciones ?>
                    </td>
                    <td class="<?= $clases_col['flipbooks'] ?>">
                        <?= $row_plus->cant_flipbooks ?>
                    </td>
                    <td class="<?= $clases_col['cuestionarios'] ?>">
                        <?= $row_plus->cant_cuestionarios ?>
                    </td>
                    
                    <td>
                        <?= $row_resultado->descripcion ?>
                    </td>

                    <td>
                        <?= $this->Pcrn->fecha_formato($row_resultado->editado, 'M-d') ?>
                        <span class="suave">
                            (Hace <?= $this->Pcrn->tiempo_hace($row_resultado->editado) ?>)
                        </span>
                    </td>
                    
                    <td class="hidden-xs">
                        <?php if ( $editable ){ ?>
                            <?= anchor("kits/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>    
</div>

<?php $this->load->view('app/modal_eliminar'); ?>