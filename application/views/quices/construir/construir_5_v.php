<?php

    $agregar = TRUE;
    if ( $row->cant_elementos >= 3 ) { $agregar = FALSE; }
    
    //Formulario
    
    $att_texto = array(
        'name' => 'texto',
        'id' => 'texto',
        'rows' => 4,
        'placeholder' => 'Escriba aquí el enunciado de la pregunta',
        'class' => 'form-control'
    );
    
    $opciones[0] = 'No';
    $opciones[1] = 'Sí';
    
    //Imagen
    $att_img = array(
        'src' => $carpeta_imagenes . $imagenes->row()->archivo,
        'height' => '100%'
    );
?>

<script type="text/javascript" src="<?php echo URL_RESOURCES ?>js/Math.uuid.js"></script>

<script>
    //Variables
    var quiz_id = <?= $row->id ?>;
    var tipo_id = 1;
    var orden = <?= $row->cant_elementos ?>;
    var texto = '';
    var detalle = '';
    var clave = '';
    
    var cant_elementos = <?= $row->cant_elementos ?>;
    var id_alfanumerico = Math.uuid(16, 16);
    var arr_elementos = JSON.parse('<?= $arr_elementos ?>');
</script>

<script>
    $(document).ready(function(){
        
        $('#guardar_elemento').click(function(){
            cargar_variables();
            guardar_elemento();
        });
        
        $('.eliminar_elemento').click(function(){
            id_alfanumerico = $(this).attr('id').substring(9);  //Quitar caracteres de "eliminar_"
            eliminar_elemento(id_alfanumerico);
            $('#elemento_' + id_alfanumerico).hide('slow');
        });
        
        $('.editar_elemento').click(function(){
            orden = $(this).attr('id').substring(7);  //Quitar caracteres de "editar_"
            id_alfanumerico = arr_elementos[orden]['id_alfanumerico'];
            cargar_formulario();
            $('#guardar_elemento').html('Actualizar');
            $('#texto').focus();
        });
        
        $('#nuevo_elemento').click(function(){
            id_alfanumerico = Math.uuid(16, 16);
            orden = cant_elementos;
            limpiar_formulario();
            $('#guardar_elemento').html('Agregar');
            $('#texto').focus();
        });    
    });
</script>

<script>
    
    function guardar_elemento(){
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>quices/guardar_elemento',
            data: {
                id_alfanumerico : id_alfanumerico,
                quiz_id : quiz_id,
                tipo_id : tipo_id,
                orden : orden,
                texto : texto,
                detalle : '',
                clave : clave
            },
            success: function(){
                //Actualizar la ventana
                window.location = '<?= base_url() ?>quices/construir/' + quiz_id;
            }
        });
    }
    
    function eliminar_elemento()
    {
        $.ajax({  
            type: 'POST',
            url: '<?= base_url() ?>quices/eliminar_elemento/' + id_alfanumerico
        });
    }
    
    function limpiar_formulario()
    {
        $('#texto').val('');
        $('#clave').val('');
    }
    
    function cargar_formulario()
    {
        $('#texto').val( arr_elementos[orden]['texto'] );
        $('#clave').val( arr_elementos[orden]['clave'] );
    }
    
    function cargar_variables()
    {
        texto = $('#texto').val();
        clave = $('#clave').val();
    }
</script>

<div class="row">
    <div class="col col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="tabla-transparente" width="100%">
                    <tbody>
                        <tr width="150px">
                            <td>Enunciado</td>
                            <td><?= form_textarea($att_texto) ?></td>
                        </tr>

                        <tr>
                            <td><span class="resaltar">Correcta</span></td>
                            <td><?= form_dropdown('clave', $opciones, '0', 'id="clave" class="form-control"') ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <span class="btn btn-primary" id="guardar_elemento">Agregar</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr/>
                
                <div class="sep3">
                    <?php if ( $imagenes->num_rows() == 0 ){ ?>
                        <?= $this->load->view('quices/construir/form_imagen_v') ?>
                    <?php } else { ?>
                        <?= $this->Pcrn->anchor_confirm("quices/eliminar_archivo/{$quiz_id}/{$imagenes->row()->id_alfanumerico}", 'Eliminar', 'class="btn btn-warning" title="Eliminar imagen"', '¿Confirma la eliminación de esta imagen?') ?>
                        <div style="max-width: 800px;" class="div2">
                            <?= img($att_img) ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                    <div id="elemento_<?= $row_elemento->id_alfanumerico ?>">

                        <div class="pull-right">
                            <span class="a4 editar_elemento" id="editar_<?= $row_elemento->orden ?>" title="Editar elemento"><i class="fa fa-pencil"></i></span>
                            <span class="a4 eliminar_elemento" id="eliminar_<?= $row_elemento->id_alfanumerico ?>" title="Eliminar elemento"><i class="fa fa-times"></i></span>
                        </div>

                        <p>
                            <span class="etiqueta informacion"><?= ($row_elemento->orden + 1)?></span>
                            <span class="etiqueta exito w2"><?= $opciones[$row_elemento->clave] ?></span> 
                        </p>

                        <p><?= $row_elemento->texto ?></p>

                        <hr/>
                    </div>
                <?php endforeach ?>

                <?php if ( $agregar ){ ?>
                    <p>
                        <span class="btn btn-info" id="nuevo_elemento">Nuevo</span>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>