<?php 
    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    
    $att_submit = array(
        'class' => 'btn btn-block btn-success',
        'value' => 'Agregar'
    );
    
    if ( $item_id > 0 ) 
    {
        $att_submit = array(
            'class' => 'btn btn-block btn-info',
            'value' => 'Actualizar'
        );
    }

    $opciones_padre = $this->Item_model->opciones_id("categoria_id = {$categoria_id}", 'Seleccione el padre');
    
    $att_id_interno = array(
        'id' => 'campo-id_interno',
        'name' => 'id_interno',
        'required' => TRUE,
        'type' => 'text',
        'class' => 'form-control',
        'value' => $id_interno,
        'placeholder' => 'Escriba el código',
        'title' => 'Escriba el código del item'
    );
    
    $att_item = array(
        'id' => 'campo-item',
        'name' => 'item',
        'required' => TRUE,
        'autofocus' => TRUE,
        'type' => 'text',
        'class' => 'form-control',
        'value' => $valores_form['item'],
        'placeholder' => 'Nombre del elemento',
        'title' => 'Escriba el item'
    );
    
    $att_descripcion = array(
        'id' => 'campo-descripcion',
        'name' => 'descripcion',
        'required' => TRUE,
        'type' => 'text',
        'rows' => 2,
        'class' => 'form-control',
        'value' => $valores_form['descripcion'],
        'placeholder' => 'Escriba la descripción',
        'title' => 'Escriba la descripción'
    );
    
    $att_item_largo = array(
        'id' => 'campo-item_largo',
        'name' => 'item_largo',
        'required' => TRUE,
        'type' => 'text',
        'class' => 'form-control',
        'value' => $valores_form['item_largo'],
        'placeholder' => 'Nombre largo',
        'title' => 'Escriba la descripción'
    );
    
    $att_item_corto = array(
        'id' => 'campo-item_corto',
        'name' => 'item_corto',
        'required' => TRUE,
        'type' => 'text',
        'class' => 'form-control',
        'value' => $valores_form['item_corto'],
        'placeholder' => 'Nombre corto',
        'title' => 'Escriba el nombre corto'
    );
    
    $att_item_grupo = array(
        'id' => 'campo-item_grupo',
        'name' => 'item_grupo',
        'type' => 'text',
        'class' => 'form-control',
        'value' => $valores_form['filtro'],
        'placeholder' => 'Grupo del elemento',
        'title' => 'Grupo del elemento'
    );
    
    $att_filtro = array(
        'id' => 'campo-filtro',
        'name' => 'filtro',
        'type' => 'text',
        'class' => 'form-control',
        'value' => $valores_form['item_grupo'],
        'placeholder' => 'Filtros del elemento',
        'title' => 'Escriba el grupo del ítem'
    );
    
    $att_abreviatura = array(
        'id' => 'campo-abreviatura',
        'name' => 'abreviatura',
        'required' => TRUE,
        'autofocus' => TRUE,
        'type' => 'text',
        'class' => 'form-control',
        'value' => $valores_form['abreviatura'],
        'placeholder' => 'Escriba la abreviatura',
        'title' => 'Escriba la abreviatura'
    );
    
    $att_slug = array(
        'id' => 'campo-slug',
        'name' => 'slug',
        'required' => TRUE,
        'autofocus' => TRUE,
        'type' => 'text',
        'class' => 'form-control',
        'value' => $valores_form['slug'],
        'placeholder' => 'Escriba la slug',
        'title' => 'Escriba la slug'
    );
  
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    
    var base_url = '<?= base_url() ?>';
    var nombre_item = '<?= $valores_form['item'] ?>';
    
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#campo-item').change(function(){
            nombre_item = $('#campo-item').val();
            act_abreviatura();  //Actualizar campo abreviatura
            act_slug();         //Actualizar campo slug
            act_item_largo();        //Actualizar item largo
            act_item_corto();        //Actualizar item corto
        });
        
        $('#btn_autocompletar').click(function(){
            act_abreviatura();  //Actualizar campo abreviatura
            act_slug(); 
            act_item_largo();        //Actualizar item largo
            act_item_corto();        //Actualizar item corto
        });
        
    });
    
// Funciones
//-----------------------------------------------------------------------------

    function act_abreviatura()
    {
        if ( $('#campo-abreviatura').val().length == 0 )
        {
            abreviatura = nombre_item.substr(0,3);
            abreviatura = abreviatura.toLowerCase();
            $('#campo-abreviatura').val(abreviatura);
        }
    }
    
    function act_item_largo()
    {
        if ( $('#campo-item_largo').val().length == 0 )
        {
            $('#campo-item_largo').val(nombre_item);
        }
    }
    
    function act_item_corto()
    {
        if ( $('#campo-item_corto').val().length == 0 )
        {
            $('#campo-item_corto').val(nombre_item);
        }
    }
    
    //Establece campo slug
    function act_slug()
    {
        //Si está vacío
        if ( $('#campo-slug').val().length == 0 )
        {
            $.ajax({        
                type: 'POST',
                url: base_url + 'app/slug_unico' + '/',
                data: {
                    texto : nombre_item,
                    tabla : 'item',
                    campo : 'item'
                },
                success: function(rta){
                    $('#campo-slug').val(rta);
                }
            });
        }
    }
    
</script>

<?= form_open($destino_form, $att_form) ?>
    <?= form_hidden('categoria_id', $categoria_id) ?>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?= form_submit($att_submit) ?>
        </div>
    </div>

    <div class="form-group">
        <label for="item" class="col-sm-4 control-label">Código</label>
        <div class="col-sm-8">
            <?= form_input($att_id_interno); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="item" class="col-sm-4 control-label">Nombre</label>
        <div class="col-sm-8">
            <?= form_input($att_item); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="descripcion" class="col-sm-4 control-label">Descripción</label>
        <div class="col-sm-8">
            <?= form_textarea($att_descripcion); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="item_grupo" class="col-sm-4 control-label">Grupo</label>
        <div class="col-sm-8">
            <?= form_input($att_item_grupo); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="filtro" class="col-sm-4 control-label">Filtros</label>
        <div class="col-sm-8">
            <?= form_input($att_filtro); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="abreviatura" class="col-sm-4 control-label">Abreviatura</label>
        <div class="col-sm-8">
            <?= form_input($att_abreviatura); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="slug" class="col-sm-4 control-label">Slug</label>
        <div class="col-sm-8">
            <?= form_input($att_slug); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="slug" class="col-sm-4 control-label">Padre</label>
        <div class="col-sm-8">
            <?= form_dropdown('padre_id', $opciones_padre, $valores_form['padre_id'], 'class="form-control chosen-select"') ?>
        </div>
    </div>

    <div class="form-group">
        <label for="item_largo" class="col-sm-4 control-label">Nombre largo</label>
        <div class="col-sm-8">
            <?= form_input($att_item_largo); ?>
        </div>
    </div>

    <div class="form-group">
        <label for="item_corto" class="col-sm-4 control-label">Nombre corto</label>
        <div class="col-sm-8">
            <?= form_input($att_item_corto); ?>
        </div>
    </div>

    

<?= form_close() ?>