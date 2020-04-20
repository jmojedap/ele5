<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php
// Configuración formulario
//-----------------------------------------------------------------------------
      
    $elemento_s = 'lugar';  //Elemento en singular
    $elemento_p = 'lugares'; //Elemento en plural
    $controlador = $this->uri->segment(1);

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

        $att_texto_busqueda = array(
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
        $opciones_tipo = $this->Lugar_model->opciones_tipo_lugar('Tipo de lugar');
        
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
        
        $seleccionados_todos = $this->Pcrn->query_to_str($resultados, 'id');
        
    //Clases columnas
        $clases_col['tipo'] = 'hidden-xs hidden-sm';
        $clases_col['region'] = 'hidden-xs hidden-sm';
        $clases_col['pais'] = 'hidden-xs hidden-sm';
        $clases_col['continente'] = 'hidden-xs hidden-sm';
        $clases_col['botones'] = 'hidden-xs hidden-sm';

    //Lugares
      $arr_tipo_lugar = $this->Lugar_model->arr_tipo_lugar();
?>

<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var busqueda_str = '<?= $busqueda_str ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?= $seleccionados_todos ?>';
    var registro_id = 0;

    var usuario_id = 0;
    var inactivo = 0;
        
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
            url: base_url + 'usuarios/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'usuarios/explorar/?' + busqueda_str;
            }
        });
    }
</script>

<?php $this->load->view($vista_menu) ?>

<div class="bs-caja">
    <div class="row">
        <div class="col-md-6 sep2">
            <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
            <?= form_input($att_texto_busqueda) ?>
            <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control"') ?>
            <?= form_submit($att_submit) ?>
            <?= form_close() ?>
        </div>
        
        <div class="col-md-3 col-xs-6 sep2">
            <div class="btn-toolbar" role="toolbar" aria-label="...">
                <div class="btn-group" role="group" aria-label="...">
                    <a class="btn btn-warning" title="Eliminar los <?= $elemento_s ?> seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </div>

                <div class="btn-group hidden-xs" role="group">
                    <?= anchor("{$controlador}/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
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
    
    <table class="table table-responsive table-hover" cellspacing="0">
        <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="50px;">ID</th>

                <th><?= $elemento_p ?></th>
                
                <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
                <th class="<?= $clases_col['region'] ?>">Dpto/Estado</th>
                <th class="<?= $clases_col['pais'] ?>">País</th>
                <th class="<?= $clases_col['continente'] ?>">Continente</th>

                <th width="35px" class="<?= $clases_col['botones'] ?>"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
                <?php
                    //Variables
                        $nombre_elemento = "{$row_resultado->nombre_lugar}";
                        $link_elemento = anchor("{$controlador}/index/$row_resultado->id", $nombre_elemento);
                        //$editable = $this->E_model->editable($row_resultado->id);

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
                    
                    <td class="<?= $clases_col['tipo'] ?>">
                        <?= $arr_tipo_lugar[$row_resultado->tipo_id] ?>
                    </td>
                    <td class="<?= $clases_col['region'] ?>"><?= $row_resultado->region ?></td>
                    <td class="<?= $clases_col['pais'] ?>"><?= $row_resultado->pais ?></td>
                    <td class="<?= $clases_col['continente'] ?>">
                        <?= $this->App_model->nombre_lugar($row_resultado->continente_id) ?>
                    </td>

                    <td class="<?= $clases_col['botones'] ?>">
                        <?= anchor("{$controlador}/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>    
</div>

<?php $this->load->view('app/modal_eliminar'); ?>