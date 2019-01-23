<?php
    
    //Formulario
    $att_texto = array(
        'name' => 'texto',
        'id' => 'texto',
        'rows' => 4,
        'placeholder' => 'Escriba aquí la definición de la palabra',
        'class' => 'form-control'
    );
    
    $att_detalle = array(
        'name' => 'detalle',
        'id' => 'detalle',
        'class' => 'form-control',
        'placeholder' => 'Escriba aquí la palabra'
    );
    
    //Imagen
    $att_img = array(
        'src' => $imagen['src'],
        'height' => '100%'
    );
    
    //Imagen elemento
    $att_img_elemento = array(
        'width' =>  '100px'
    );
?>

<script type="text/javascript" src="<?php echo URL_RECURSOS ?>js/Math.uuid.js"></script>

<script>
    //Variables
    var id_alfanumerico = Math.uuid(16, 16);
    var quiz_id = <?= $row->id ?>;
    var tipo_id = 1;
    var orden = <?= $row->cant_elementos ?>;
    var texto = '';
    var detalle = '';
    var clave = '';
    
    var cant_elementos = <?= $row->cant_elementos ?>;
    var arr_elementos = JSON.parse('<?= $arr_elementos ?>');
</script>

<script>
    $(document).ready(function(){
        
        $('.form_img').hide();
        
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
            $('#detalle').focus();
        });
        
        $('.mostrar_img').click(function(){
            id_alfanumerico = $(this).attr('id').substring(12);  //Quitar caracteres de "mostrar_img_"
            $('#form_img_' + id_alfanumerico).toggle('fast');
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
                detalle :detalle,
                clave : orden
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
        $('#detelle').val('');
    }
    
    function cargar_formulario()
    {
        $('#texto').val( arr_elementos[orden]['texto'] );
        $('#detalle').val( arr_elementos[orden]['detalle'] );
    }
    
    function cargar_variables()
    {
        texto = $('#texto').val();
        detalle = $('#detalle').val();
    }
</script>

<div class="row">
    <div class="col col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="tabla-transparente" width="100%">
                    <tbody>
                        <tr width="150px">
                            <td>Palabra</td>
                            <td><?= form_input($att_detalle) ?></td>
                        </tr>

                        <tr>
                            <td>Definición</td>
                            <td><?= form_textarea($att_texto) ?></td>
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
                        <?= $this->Pcrn->anchor_confirm("quices/eliminar_archivo/{$quiz_id}/{$imagen['id_alfanumerico']}", 'Eliminar', 'class="btn btn-warning" title="Eliminar imagen"', '¿Confirma la eliminación de esta imagen?') ?>
                        <div style="max-width: 800px;" class="sep2">
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
                <?php if ( $this->session->flashdata('mensaje_elemento') ){ ?>
                    <?= $this->session->flashdata('mensaje_elemento') ?>
                <?php } ?>

                <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                    <div id="elemento_<?= $row_elemento->id_alfanumerico ?>">
                        <span style="display: none"  id="clave_<?= $row_elemento->id_alfanumerico ?>"><?= $row_elemento->clave ?></span>

                        <div class="pull-right">

                            <span class="a4 editar_elemento" id="editar_<?= $row_elemento->orden ?>" title="Editar elemento"><i class="fa fa-pencil"></i></span>
                            <span class="a4 eliminar_elemento" id="eliminar_<?= $row_elemento->id_alfanumerico ?>" title="Eliminar elemento"><i class="fa fa-times"></i></span>
                        </div>


                        <span class="etiqueta informacion"><?= ($row_elemento->orden + 1)?></span>
                        <span class="etiqueta exito"><?= $row_elemento->detalle ?></span>

                        <p>
                            <?= $row_elemento->texto ?>
                        </p>



                        <div class="sep2">
                            <?php if ( is_null($row_elemento->archivo) ){ ?>
                                <span class="btn btn-primary mostrar_img" id="mostrar_img_<?= $row_elemento->id_alfanumerico ?>" title="Editar imagen del elemento"><i class="fa fa-picture-o"></i> Agregar imagen</span>
                            <?php } else { ?>

                                <?= $this->Pcrn->anchor_confirm("quices/eliminar_archivo/{$quiz_id}/{$row_elemento->id_alfanumerico}", '<i class="fa fa-trash-o"></i>', 'class="btn btn-warning" title="Eliminar imagen"', '¿Confirma la eliminación de esta imagen?') ?>
                                <div style="max-width: 800px;" class="sep2">
                                    <?php $att_img_elemento['src'] = $carpeta_imagenes . $row_elemento->archivo ?>
                                    <?= img($att_img_elemento) ?>
                                </div>
                            <?php } ?>
                        </div>




                        <div class="form_img" id="form_img_<?= $row_elemento->id_alfanumerico ?>">
                            <?php $data_form['elemento_id'] = $row_elemento->id ?>
                            <?php $this->load->view('quices/construir/form_imagen_elemento_v', $data_form); ?>
                        </div>

                        <hr/>
                    </div>
                <?php endforeach ?>
                <p>
                    <span class="btn btn-info" id="nuevo_elemento">Nuevo</span>
                </p>
            </div>
        </div>
    </div>
</div>