<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/icheck'); ?>
<?php $this->load->view('assets/toastr'); ?>

<?php
//Niveles
    $att_check_todos = array(
        'id'    => 'check_todos',
        'checked' => FALSE
    );

    $att_check = array(
        'class' =>  'check_registro',
        'checked' => FALSE
    );

    $niveles = $this->db->get_where('item', 'categoria_id = 3');
    $seleccionados_todos = '-'. $this->Pcrn->query_to_str($niveles, 'id_interno', ',');   //Para selección masiva de todos los niveles
    $arr_niveles = explode(',', $row->texto_2);

//Elementos formulario
    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    $att_nombre_post = array(
        'id'     => 'nombre_post',
        'name'   => 'nombre_post',
        'class'  => 'form-control',
        'value'  => $row->nombre_post,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del post'
    );
    
    $att_resumen = array(
        'id'     => 'campo-resumen',
        'name'   => 'resumen',
        'class'  => 'form-control',
        'required'  => TRUE,
        'value'  => $row->resumen,
        'placeholder'   => 'Descripción del contenido',
        'rows' => 3
        
    );
    
    $att_contenido = array(
        'id'     => 'contenido',
        'name'   => 'contenido',
        'class'  => 'form-control',
        'value'  => $row->contenido,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del post'
    );
    
    $att_texto_1 = array(
        'id'     => 'campo-texto_1',
        'name'   => 'texto_1',
        'class'  => 'form-control',
        'value'  => $row->texto_1
    );
    
    $att_texto_2 = array(
        'id'     => 'campo-texto_2',
        'name'   => 'texto_2',
        'class'  => 'form-control',
        'value'  => $row->texto_2
    );
    
    $att_archivo = array(
        'name' => 'archivo',
        'required' => TRUE
    );
    
//Opciones
    $opciones_cap = $this->Item_model->opciones('categoria_id = 152', 'Categoría');
    $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Área');
    $opciones_tipo_cap = $this->Item_model->opciones('categoria_id = 153', 'Tipo contenido');
    
    $att_submit = array(
        'class'  => 'btn btn-block btn-success',
        'value'  => 'Guardar'
    );
    
//Archivo
    $row_archivo = $this->Pcrn->registro_id('archivo', $row->imagen_id);

//Clase 
    $clase_source = 'btn btn-default';
    $link_source = "posts/editar/{$row->id}/source";
    if ( $this->uri->segment(4) == 'source' ) 
    {
        $clase_source = 'btn btn-primary';
        $link_source = "posts/editar/{$row->id}";
    }
    
?>

<script>
//Variables
//---------------------------------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var post_id = '<?= $row->id ?>';
    var controlador = 'posts';
    var seleccionados = '<?= $row->texto_2 ?>';
    var seleccionados_todos = '<?= $seleccionados_todos ?>';
    var resultado = '<?php echo $this->uri->segment(4) ?>';
    
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#formulario').submit(function(){
            ap_crud();
            return false;
        });
        
        $('.check_registro').on('ifChanged', function(){
            registro_id = ',' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            $('#campo-texto_2').val(seleccionados.substring(0));
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
            
            $('#campo-texto_2').val(seleccionados.substring(0));
        });
        
        if ( resultado == 'success' )
        {
            toastr["success"]('Los cambios fueron guardados');
        }
        
    });
    
// Funciones
//-----------------------------------------------------------------------------
    
    //Ajax
    function ap_crud()
    {
        $.ajax({
            type: 'POST',
            url: base_url + controlador + '/ap_crud/actualizar/' + post_id,
            data: $('#formulario').serialize(),
            success: function(response){
                if ( response.status == 1 )
                {
                    toastr["success"]('Los cambios fueron guardados');    
                }
            }
        });
    }
    
</script>

<div class="row">
    <div class="col col-md-8">
        <div class="card mb-2">
            <div class="card-header">
                Datos generales
            </div>
            <form class="form-horizontal" id="formulario">
                <div class="card-body">
                    

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for="nombre_post">Título * </label>
                        <div class="col-sm-9">
                            <?= form_input($att_nombre_post) ?>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for="resumen">Descripción * </label>
                        <div class="col-sm-9">
                            <?= form_textarea($att_resumen) ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for="referente_3_id">Tipo * </label>
                        <div class="col-sm-9">
                            <?= form_dropdown('referente_3_id', $opciones_tipo_cap, $row->referente_3_id, 'class="form-control" required') ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for="referente_1_id">Categoría * </label>
                        <div class="col-sm-9">
                            <?= form_dropdown('referente_1_id', $opciones_cap, $row->referente_1_id, 'class="form-control" required') ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for="area_id">Área * </label>
                        <div class="col-sm-9">
                            <?= form_dropdown('referente_2_id', $opciones_area, $row->referente_2_id, 'class="form-control" required') ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for="texto_1">URL</label>
                        <div class="col-sm-9">
                            <?= form_input($att_texto_1) ?>
                        </div>
                    </div>
                    <div class="form-group d-none">
                        <label class="col-sm-3 col-form-label text-right" for="texto_2">Niveles</label>
                        <div class="col-sm-9">
                            <?= form_input($att_texto_2) ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for=""></label>
                        <div class="col-sm-9">
                            <button class="btn btn-success w120p" type="submit">
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                Archivo asociado
            </div>
            <div class="card-body">
                <?php if ( ! is_null($row_archivo) ) { ?>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right" for="imagen_id">Archivo</label>
                        <div class="col-sm-9">
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-default" style="width: 450px;">
                                    <?= $row_archivo->titulo_archivo ?><?= $row_archivo->ext ?>
                                </button>
                                <?php echo $this->Pcrn->anchor_confirm("posts/eliminar_imagen/{$row->id}/{$row_archivo->id}/ap_editar", '<i class="fa fa-trash"></i>', 'class="btn btn-warning" title="Eliminar archivo"', '¿Confirma la eliminación de este archivo?') ?>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php echo form_open_multipart("posts/cargar_archivo/{$row->id}/ap_editar", $att_form) ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right" for="archivo">Archivo</label>
                            <div class="col-sm-6">
                                <?= form_upload($att_archivo) ?>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-success btn-block" id="btn_cargar_archivo">
                                    <i class="fa fa-upload"></i>
                                    Cargar
                                </button>
                            </div>
                        </div>
                    <?php echo form_close() ?>
                <?php } ?>
            </div>
        </div>
        
    </div>
    <div class="col col-md-4">
        <div class="card">
            <div class="card-header">
                Niveles
            </div>
            <div class="card-body">
                <label>
                    <?= form_checkbox($att_check_todos) ?>
                    Todos
                </label>
                <br/>
                <?php foreach ($niveles->result() as $row_nivel) { ?>
                    <?php 
                        //Checkbox
                        $att_check['data-id'] = $row_nivel->id_interno;
                        $att_check['checked'] = FALSE;
                        if ( in_array($row_nivel->id_interno, $arr_niveles) ) { $att_check['checked'] = TRUE; }
                    ?>
                    <label>
                        <?= form_checkbox($att_check) ?>
                        <?= $row_nivel->item_largo ?>
                    </label>
                    <br/>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('comunes/modal_eliminar_simple'); ?>