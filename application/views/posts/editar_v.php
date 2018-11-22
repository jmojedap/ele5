<?php


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
        'value'  => $row->contenido,
        'required' => TRUE,
        'placeholder'   => 'Escriba el título del post'
    );
    
    $att_fecha = array(
        'id'     => 'fecha',
        'name'   => 'fecha',
        'class'  => 'form-control',
        'type'  =>  'date',
        'size' =>   9,
        'value'  => $this->Pcrn->fecha_formato($row->fecha, 'Y-m-d')
    );
    
    $att_publicado = array(
        'id'     => 'publicado',
        'name'   => 'publicado',
        'class'  => 'form-control',
        'type'  =>  'date',
        'size' =>   9,
        'value'  => $this->Pcrn->fecha_formato($row->publicado, 'Y-m-d')
    );
    
    $att_imagen_id = array(
        'id'     => 'imagen_id',
        'name'   => 'imagen_id',
        'class'  => 'form-control',
        'value'  => $row->imagen_id,
        'placeholder'   => 'Escriba el ID de la imagen asociada'
    );
    
    //Opciones
        $opciones_ecomentarios = $this->Item_model->opciones('categoria_id = 41', 'Estado cuestionarios');
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 33', 'Tipo de post');
    
    $att_submit = array(
        'class'  => 'btn btn-block btn-info',
        'value'  => 'Actualizar'
    );
    
    $campos_general = array(
        'texto_1' => 'Texto 1',
        'texto_2' => 'Texto 2',
        'referente_1_id' => 'Referente 1 ID',
        'referente_2_id' => 'Referente 2 ID',
        'referente_3_id' => 'Referente 3 ID',
    );
    
    //Clase 
        $clase_source = 'btn btn-default';
        $link_source = "posts/editar/{$row->id}/source";
        if ( $this->uri->segment(4) == 'source' ) { 
            $clase_source = 'btn btn-primary';
            $link_source = "posts/editar/{$row->id}";
        }
    
?>

<script src="//cdn.ckeditor.com/4.5.4/standard/ckeditor.js"></script>
<?= form_open("posts/actualizar/{$row->id}", $att_form) ?>
    
<?php if ( $this->uri->segment(4) == 'actualizado' ){ ?>
    <div class="alert alert-success">
        <i class="fa fa-info"></i>
        El post fue actualizado
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-9">
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="nombre_post">Título</label>
                    <?= form_input($att_nombre_post) ?>
                </div>

                <div class="form-group">
                    <div class="sep2">
                        <?= anchor($link_source, '<i class="fa fa-code"></i> Fuente', 'class="' . $clase_source . '" title=""') ?>
                    </div>
                    <?= form_textarea($att_contenido) ?>
                </div>

                <div class="form-group">
                    <label for="resumen">Resumen</label>
                    <textarea name="resumen" class="form-control" rows="3"><?= $row->resumen ?></textarea>
                </div>
            </div>
        </div>
        

    </div>

    <div class="col-md-3 form-horizontal">
        <div class="sep1">
            <?= form_submit($att_submit) ?>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="tipo_id">Tipo</label>
                    <div class="col-sm-9">
                        <?= form_dropdown('tipo_id', $opciones_tipo, $row->tipo_id, 'class="form-control"') ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="fecha">Fecha publicado</label>
                    <div class="col-sm-9">
                        <?= form_input($att_publicado) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="imagen_id">Imagen ID</label>
                    <div class="col-sm-9">
                        <?= form_input($att_imagen_id) ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="estado_comentarios">Comentarios</label>
                    <div class="col-sm-9">
                        <?= form_dropdown('estado_comentarios', $opciones_ecomentarios, $row->estado_comentarios, 'class="form-control"') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="fecha">Fecha</label>
                    <div class="col-sm-9">
                        <?= form_input($att_fecha) ?>
                    </div>
                </div>

                <?php foreach ($campos_general as $nombre_campo => $titulo_campo) : ?>
                    <?php
                        $att_campo = array(
                            'id'     => $nombre_campo,
                            'name'   => $nombre_campo,
                            'class'  => 'form-control',
                            'value'  => $row->$nombre_campo,
                            'placeholder'   => $nombre_campo
                        );
                    ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="<?= $nombre_campo ?>"><?= $titulo_campo ?></label>
                        <div class="col-sm-9">
                            <?= form_input($att_campo) ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

    </div>
</div>
    
<?= form_close('') ?>

<?php if ( $this->uri->segment(4) != 'source' ){ ?>
    <script>
        CKEDITOR.replace( 'contenido' );
    </script>
<?php } ?>

