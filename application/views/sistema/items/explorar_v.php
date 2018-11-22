<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

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
        
    //Array activo
        $arr_activo[0] = '-';
        $arr_activo[1] = '<i class="fa fa-check"></i>';
        
    //Categorías
        $arr_categorias = $this->Item_model->categorias();
        $opciones_categorias = array_merge(array('' => "(Seleccione la categoría)"), $arr_categorias);
?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var controlador = '<?= $this->uri->segment(1); ?>';
        var busqueda_str = '<?= $busqueda_str ?>';
        var seleccionados = '';
        var seleccionados_todos = '<?= $seleccionados_todos ?>';
        var registro_id = 0;
        
</script>

<script>
    $(document).ready(function(){
        
        $('.check_registro').on('ifChanged', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
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
            
            $('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
    });
</script>

<script>
    //Ajax
    function eliminar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + controlador + '/eliminar_seleccionados/',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + controlador + '/explorar/?' + busqueda_str;
            }
        });
    }
</script>


<?= $this->load->view('sistema/items/explorar_menu_v'); ?>

<!--HERRAMIENTAS-->
<!------------------------------------------------------------------------------------------->

<div class="bs-caja">
    <div class="row">
        <div class="col-md-6 sep2">
            <?= form_open($destino_form, $att_form) ?>
                <?= form_input($att_q) ?>
                <?= form_dropdown('cat', $opciones_categorias, $busqueda['cat'], 'class="form-control chosen-select"') ?>
                <?= form_submit($att_submit) ?>
            <?= form_close() ?>
        </div>
        
        <div class="col-md-3 sep2">
            <a class="btn btn-warning hidden-xs hidden-sm" title="Eliminar los elementos seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                <i class="fa fa-trash-o"></i>
            </a>
            <?= anchor("{$this->uri->segment(1)}/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success hidden-xs hidden-sm" title="Exportar resultados a archivo de MS Excel"') ?>
        </div>
        
        <div class="col-md-3 sep2 col-sm-12">
            <div class="pull-right">
                <!--<p id="seleccionados"></p>-->
                <?= $this->pagination->create_links(); ?>       
            </div>
        </div>
    </div>
    
    <table class="table table-hover table-responsive" cellspacing="0">
        <thead>
            <tr>
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="45px;" class="warning">ID</th>
                <th>Nombre item</th>
                <th>ID Interno</th>
                <th class="hidden-xs hidden-sm">Descripción</th>
                <th class="hidden-xs hidden-sm">Categoría</th>
                <th class="hidden-xs hidden-sm" width="35px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_item){ ?>
            <?php
                //Variables
                    $nombre_item = $row_item->item;
                    $link_item = anchor("items/editar/edit/$row_item->id", $nombre_item);

                //Checkbox
                    $att_check['data-id'] = $row_item->id;

            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    <td class="warning"><span class="etiqueta primario w1"><?= $row_item->id ?></span></td>
                    <td><?= $link_item ?></td>
                    <td><?= $row_item->id_interno ?></td>
                    <td class="hidden-xs hidden-sm"><?= $row_item->descripcion ?></td>
                    <td class="hidden-xs hidden-sm"><?= $categorias_item[$row_item->categoria_id] ?></td>
                    <td class="hidden-xs hidden-sm">
                        <?= anchor("items/editar/edit/{$row_item->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>

<?= $this->load->view('app/modal_eliminar'); ?>