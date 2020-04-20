<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php
    $carpeta_iconos = RUTA_IMG . 'flipbook/';
    $carpeta_uploads = base_url(RUTA_UPLOADS);
    
    $this->db->where('categoria_id', 20);
    $tipos_archivo = $this->db->get('item');
    $iconos = $this->Pcrn->query_to_array($tipos_archivo, 'slug', 'id');
    $carpetas = $this->Pcrn->query_to_array($tipos_archivo, 'slug', 'id');
    
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


        //Opciones de dropdowns
        $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Todas las áreas');
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
        $opciones_tipo = $this->Item_model->opciones_id('categoria_id = 20 AND item_grupo = 1', 'Todos los tipos');
        

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
            $seleccionados_todos .= '-' . $row_resultado->recurso_id;
        }
        
    //Clases columnas
        $clases_col['disponible'] = 'hidden-xs';
        $clases_col['tema'] = 'hidden-xs';
        $clases_col['nivel_area'] = 'hidden-xs hidden-sm';
        $clases_col['usuario'] = 'hidden-xs hidden-sm';
        $clases_col['editado'] = 'hidden-xs hidden-sm';
?>

<?php $this->load->view('recursos/menu_archivos_v') ?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var busqueda_str = '<?= $busqueda_str ?>';
        var seleccionados = '';
        var seleccionados_todos = '<?= $seleccionados_todos ?>';
        var registro_id = 0;
        
        var usuario_id = 0;
        var activo = 0;
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
    });
</script>

<script>
    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'recursos/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                //window.location = base_url + 'recursos/archivos/?' + busqueda_str;
            }
        });
    }
</script>

<div class="">
    <div class="row">
        <div class="col-md-6 sep2">
            <?= form_open("recursos/archivos/", $att_form) ?>
            <?= form_input($att_q) ?>
            <?= form_dropdown('a', $opciones_area, $busqueda['a'], 'title="Filtrar por área" class="form-control chosen-select"'); ?>
            <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'title="Filtrar por nivel" class="form-control chosen-select"'); ?>
            <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'title="Filtrar por tipo de archivo" class="form-control chosen-select"'); ?>
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
                    <?= anchor("archivos/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar los ' . $cant_resultados . ' registros a archivo de MS Excel"') ?>
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
    
<table class="table table-default bg-blanco">
    <thead>
        <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
        <th width="50px;">ID</th>
        <th width="50px">Tipo</th>
        <th>Nombre archivo</th>
        <th title="Está el archivo en el servidor" class="<?= $clases_col['disponible'] ?>">Disponible</th>
        <th class="<?= $clases_col['tema'] ?>">Tema</th>
        <th class="<?= $clases_col['nivel_area'] ?>">Nivel - Área</th>
        <th class="<?= $clases_col['usuario'] ?>">Usuario</th>
        <th class="<?= $clases_col['editado'] ?>">Editado hace</th>
        <?php if ( $this->session->userdata('rol_id') <= 2 ) : ?>                
            <th width="35px" class="<?= $clases_col['editar'] ?>"></th>
        <?php endif ?>
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado) : ?>
            <?php
                $att_icono['src'] = "{$carpeta_iconos}{$iconos[$row_resultado->tipo_archivo_id]}.png";
                $ruta_archivo = URL_UPLOADS . $carpetas[$row_resultado->tipo_archivo_id] . '/' . $row_resultado->nombre_archivo;
                
                $texto_link = $row_resultado->nombre_archivo;
                $img_link = img($att_icono);
                $texto_disponible = 'No';
                $clase_disponible = 'alerta';
                
                if ( $row_resultado->disponible ) 
                {
                    $texto_disponible = 'Sí';
                    $clase_disponible = 'exito';
                    $texto_link = anchor($ruta_archivo, $row_resultado->nombre_archivo, 'class="" title="" target="_blank"');
                    $img_link = anchor($ruta_archivo, img($att_icono), 'class="" title="" target="_blank"');
                }
                
                //Checkbox
                    $att_check['data-id'] = $row_resultado->recurso_id;
            ?>
            <tr>
                <td>
                    <?= form_checkbox($att_check) ?>
                </td>
                <td class="warning"><?= $row_resultado->recurso_id ?></td>
                <td class="align_cen"><?= $img_link ?></td>
                <td><?= $texto_link ?></td>
                <td class="<?= $clases_col['disponible'] ?>">
                    <span class="etiqueta w1 <?= $clase_disponible ?>"><?= $texto_disponible ?></span>
                </td>
                <td class="<?= $clases_col['tema'] ?>">
                    <?= anchor("temas/archivos/{$row_resultado->tema_id}", $row_resultado->nombre_tema, 'class="" title=""') ?>
                </td>
                <td class="<?= $clases_col['nivel_area'] ?>">
                    <span class="etiqueta nivel w1"><?= $row_resultado->nivel ?></span>
                    <?= $this->App_model->etiqueta_area($row_resultado->area_id); ?>
                </td>
                <td class="<?= $clases_col['usuario'] ?>"><?= $this->App_model->nombre_usuario($row_resultado->usuario_id, 2) ?></td>
                <td class="<?= $clases_col['editado'] ?>"><?= $this->Pcrn->tiempo_hace($row_resultado->editado) ?></td>
                <?php if ( $this->session->userdata('rol_id') <= 2 ) : ?>                
                    <td class="<?= $clases_col['editar'] ?>">
                        <?= anchor("temas/archivos/{$row_resultado->tema_id}/edit/{$row_resultado->recurso_id}", '<i class="fa fa-pencil"></i>', 'class="a4"') ?>
                    </td>
                <?php endif ?>
            </tr>

        <?php endforeach ?>
    </tbody>
</table>

<?php $this->load->view('app/modal_eliminar'); ?>