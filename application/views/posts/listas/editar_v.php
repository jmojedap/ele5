

<?php
    /*$att_form = array(
        'class' => 'form-horizontal'
    );*/

    $att_nombre_post = array(
        'id'     => 'nombre_post',
        'name'   => 'nombre_post',
        'class'  => 'form-control',
        'value'  => $row->nombre_post,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del post'
    );
    
    $att_contenido = array(
        'id'     => 'contenido',
        'name'   => 'contenido',
        'class'  => 'form-control',
        'rows'  => '3',
        'value'  => $row->contenido,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del post'
    );
    
    $att_submit = array(
        'class'  => 'btn btn-block btn-info',
        'value'  => 'Actualizar'
    );
    
    $condicion_tablas = 'cod_tabla IN (1020, 2000, 1000, 2200)';
    $opciones_tabla = $this->App_model->arr_tabla('sis_tabla', $condicion_tablas, 'nombre_tabla', 'cod_tabla');
    
    //Clase 
        $clase_source = 'btn btn-default';
        $link_source = "posts/editar/{$row->id}/source";
        if ( $this->uri->segment(4) == 'source' ) { 
            $clase_source = 'btn btn-primary';
            $link_source = "posts/editar/{$row->id}";
        }
    
?>

<?= form_open("posts/actualizar/{$row->id}", $att_form) ?>
    
<div class="row">
    <div class="col-md-9">
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="nombre_post">Título</label>
                    <?= form_input($att_nombre_post) ?>
                </div>

                <div class="form-group">
                    <label for="nombre_post">Descripción</label>
                    <?= form_textarea($att_contenido) ?>
                </div>
            </div>
        </div>
        

    </div>

    <div class="col-md-3">
        <div class="sep1">
            <?= form_submit($att_submit) ?>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="nombre_post">Tabla de los elementos</label>
                    <?= form_dropdown('referente_1_id', $opciones_tabla, $row->referente_1_id, 'class="form-control"') ?>
                </div>
            </div>
        </div>

    </div>
</div>
    
<?= form_close('') ?>

