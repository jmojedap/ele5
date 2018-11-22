
<?php

    $att_form = array(
        'class' =>  'form-horizontal'
    );
    
    $att_nombre_flipbook = array(
        'id' => 'nombre_flipbook',
        'name'  =>  'nombre_flipbook',
        'value' => 'Contenido - ' . $row->nombre_programa . ' - ' . date('Ymdhis'),
        'class' =>  'form-control',
        'required' =>  'required',
        'placeholder' => 'Nombre del nuevo contenido que se creará a partir de este programa',
        'title' => 'Escriba el nombre del nuevo contenido que se creará a partir de este programa'
    );
    
    $opciones_tipo = $this->Item_model->opciones('categoria_id = 11', 'Tipo de contenido');
    
    $att_descripcion = array(
        'name'  =>  'descripcion',
        'value' => 'Contenido creado a partir del programa: ' . $row->nombre_programa . ', creado el ' . date('Y-M-d h:i'),
        'class' =>  'form-control',
        'rows' =>   3
    );
    
    $att_submit = array(
        'value' => 'Generar',
        'class' =>  'btn btn-primary'
    );
?>

<?php $this->load->view('programas/submenu_nuevo_flipbook_v'); ?>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>
<?php $this->load->view('comunes/validation_errors_v'); ?>

<div class="row">
    <div class="col col-sm-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                Crear un nuevo contenido con los temas del programa.
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col col-sm-9 col-sm-offset-3">
                        <p>
                            El Contenido generado tendrá las <span class="resaltar"><?= $paginas->num_rows() ?></span>
                            páginas asociadas a los temas del programa.
                        </p>
                    </div>
                </div>

                <?= form_open($destino_form, $att_form) ?>
                    <div class="form-group">
                        <label for="nombre_flipbook" class="col-sm-3 control-label">Nombre del Contenido</label>
                        <div class="col-sm-9">
                            <?= form_input($att_nombre_flipbook) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo_flipbook_id" class="col-sm-3 control-label">Tipo</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('tipo_flipbook_id', $opciones_tipo, NULL, 'class="form-control" required') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="descripcion" class="col-sm-3 control-label">Descripción</label>
                        <div class="col-sm-9">
                            <?= form_textarea($att_descripcion) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <?= form_submit($att_submit) ?>
                        </div>
                    </div>
                <?= form_close('') ?>
            </div>
        </div>

    
    </div>
    <div class="col col-sm-7">
        <?php $this->load->view('programas/flipbooks_v'); ?>
    </div>
</div>
