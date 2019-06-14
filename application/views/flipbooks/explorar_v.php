<?= $this->load->view('assets/chosen_jquery'); ?>
<?= $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'Contenido';  //Elemento en singular
    $elemento_p = 'Contenidos'; //Elemento en plural
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
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo', 'Nivel');
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 11', 'Tipo contenido');
        
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
        
    //Clases columnas
        $clases_col['leer'] = '';
        $clases_col['nivel'] = 'hidden-xs';
        $clases_col['area'] = 'hidden-xs';
        $clases_col['tipo'] = 'hidden-xs hidden-sm';
        $clases_col['taller'] = 'hidden-xs hidden-sm hidden-md';
        $clases_col['programa'] = 'hidden-xs hidden-sm hidden-md';
?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var seleccionados = '';
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
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').iCheck('check');
                $('.check_registro').each( function(key, element) {
                    seleccionados += '-' + $(element).data('id');
                });
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
        
        $('.crear_json').click(function(){
            registro_id = $(this).data('flipbook_id');
            crear_json(registro_id);
        });
        
        
    });

//FUNCIONES
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'flipbooks/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'flipbooks/explorar';
            }
        });
    }
    
    //Ajax
    function crear_json(flipbook_id){
        $.ajax({        
            type: 'POST',
            url: base_url + 'flipbooks/crear_json/' + flipbook_id,
            success: function(data){
                if ( data.ejecutado == 1 ) {
                    $('#crear_json_' + flipbook_id).toggleClass('btn-light');
                    $('#crear_json_' + flipbook_id).toggleClass('btn-success');
                    console.log('Archivo actualizado: ' + flipbook_id);
                }
            }
        });
    }
</script>

<?= $this->load->view($vista_menu) ?>

<div class="row">
    <div class="col-md-6 sep2">
        <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
        <?= form_input($att_q) ?>
        <?= form_dropdown('a', $opciones_area, $busqueda['a'], 'class="form-control chosen-select" title="Filtrar por área"'); ?>
        <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'class="form-control chosen-select" title="Filtrar por nivel"'); ?>
        <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control chosen-select" title="Filtrar por tipo de contenido"'); ?>
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
                <?= anchor("flipbooks/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
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

<div class="bs-caja-no-padding">
    <table class="table table-responsive table-hover" cellspacing="0">
        <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="50px;">ID</th>
                <th><?= $elemento_s ?></th>
                
                <th class="<?= $clases_col['leer'] ?>" width="60px">Leer</th>
                <th class="<?= $clases_col['area'] ?>">Nivel - Área</th>
                <th class="<?= $clases_col['taller'] ?>">Taller asociado</th>
                <th class="<?= $clases_col['programa'] ?>" title="Programa a partir del cual se creó este Contenido">Programa origen</th>
                
                <th class="<?php echo $clases_col['json'] ?>">
                    JSON
                </th>
                
                <th width="35px" class="hidden-xs"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = character_limiter($row_resultado->nombre_flipbook, 50);
                    $link_elemento = anchor("flipbooks/temas/$row_resultado->id", $nombre_elemento, 'title="' . $row_resultado->nombre_flipbook . '"');
                    $editable = $this->Flipbook_model->editable($row_resultado->id);
                    
                //Taller
                    $nombre_taller = $this->App_model->nombre_flipbook($row_resultado->taller_id);
                    $link_taller = '';
                    if ( ! is_null($row_resultado->taller_id) ) { $link_taller = anchor("flipbooks/paginas/{$row_resultado->taller_id}", $nombre_taller, 'class="" title=""'); }

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
                        <br/>
                        <?= $this->Item_model->nombre(11, $row_resultado->tipo_flipbook_id) ?>
                    </td>
                    
                    <td class="<?= $clases_col['leer'] ?>">
                        <?php echo anchor("flipbooks/abrir/{$row_resultado->id}", '<i class="fa fa-book"></i>', 'class="btn btn-info btn-sm" title="Leer" target="_blank"') ?>
                    </td>
                    
                    <td class="<?= $clases_col['area'] ?>">
                        <span class="etiqueta nivel w1"><?= $row_resultado->nivel ?></span>
                        <?= $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                    </td>
                    
                    <td class="<?= $clases_col['taller'] ?>">
                        <?= $link_taller  ?>
                    </td>
                    <td class="<?= $clases_col['programa'] ?>">
                        <?php if ( ! is_null($row_resultado->programa_id) ){ ?>
                            <span class="etiqueta primario"><?= $row_resultado->programa_id ?></span>
                            <?= anchor("programas/temas/{$row_resultado->programa_id}", $this->Pcrn->campo_id('programa', $row_resultado->programa_id, 'nombre_programa'), 'class="" title=""') ?>
                        <?php } ?>
                    </td>
                    
                    <td class="<?php echo $clases_col['json'] ?>">
                        <button class="btn btn-default crear_json" id="crear_json_<?php echo $row_resultado->id ?>" data-flipbook_id="<?php echo $row_resultado->id ?>" title="Actualizar archivo JSON de contenido">
                            <i class="fa fa-file-text-o"></i>
                        </button>
                    </td>
                    
                    <td class="hidden-xs">
                        <?php if ( $editable ){ ?>
                            <?= anchor("flipbooks/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>    
</div>

<?= $this->load->view('app/modal_eliminar'); ?>