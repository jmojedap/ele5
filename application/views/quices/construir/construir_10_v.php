<?php

    $cant_elementos = $elementos_quiz->num_rows();
    $cant_opciones = 2;
    
    //Formulario
    
    $att_texto = array(
        'name' => 'texto',
        'id' => 'texto',
        'rows' => 4,
        'placeholder' => 'Escriba aquí el enunciado de la pregunta',
        'class' => 'form-control',
        
    );
    
    //Opciones de respuesta
    for ($i = 0; $i < $cant_opciones; $i++) 
    {
        //Para input
        $att_opciones[] = array(
            'name' => 'opcion_' . $i,
            'id' => 'opcion_' . $i,
            'placeholder' => 'Escriba la opción ' . ($i + 1),
            'class' => 'opcion_respuesta',
            'class' => 'form-control'
        );
        
        //Opciones
        $opciones[$i] = 'Opción ' . ($i + 1);
    }
    
//Imagen
    $att_img = array(
        'src' => $carpeta_imagenes . $imagenes->row()->archivo,
        'height' => '100%'
    );
?>

<script type="text/javascript" src="<?php echo URL_RECURSOS ?>js/Math.uuid.js"></script>

<script>
    //Variables
    var id_alfanumerico = Math.uuid(16, 16);
    var quiz_id = <?= $row->id ?>;
    var tipo_id = 3;
    var orden = <?= $cant_elementos ?>;
    var texto = '';
    var detalle = '';
    var clave = '';
    
    var cant_elementos = <?= $cant_elementos ?>;
    var cant_opciones = <?= $cant_opciones ?>;
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
            alert(id_alfanumerico);
            cargar_formulario();
            $('#guardar_elemento').html('Actualizar');
            $('#detalle').focus();
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
                detalle : detalle,
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
    
    function json_opciones()
    {
        var opciones_arr = [];
        for ($i=0; $i<cant_opciones; $i++) {
            texto_opcion = $('#opcion_' + $i).val();
            if ( texto_opcion.length > 0 ){
                opciones_arr.push(texto_opcion);
            } else {
                break
            }
        }
        return JSON.stringify(opciones_arr);
    }
    
    function limpiar_formulario()
    {
        $('#texto').val('');
        $('#clave').val('');
        for ($i=0; $i<cant_opciones; $i++) {
            $('#opcion_' + $i).val('');
        }
    }
    
    function cargar_formulario()
    {
        $('#texto').val( arr_elementos[orden]['texto'] );
        $('#clave').val( arr_elementos[orden]['clave'] );
        for ($i=0; $i<cant_opciones; $i++) {
            $('#opcion_' + $i).val( arr_elementos[orden]['opcion_' + $i] );
        }
    }
    
    function cargar_variables()
    {
        texto = $('#texto').val();
        detalle = json_opciones();
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
                            <td>Pregunta</td>
                            <td><?= form_textarea($att_texto) ?></td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <h3>Opciones</h3>
                            </td>
                        </tr>

                        <?php foreach ($att_opciones as $key => $att_opcion) { ?>
                            <tr>
                                <td width="150px">Opción <?= $key + 1?></td>
                                <td><?= form_input($att_opcion) ?></td>
                            </tr>
                        <?php } ?>
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
            <h3>Párrafo completo</h3>
                <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                    <?= str_replace('#casilla', '<span class="etiqueta informacion">#casilla</span>', $row_elemento->texto) ?>
                <?php endforeach ?>

                <hr/>

                <h3>Elementos</h3>

                <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                    <div id="elemento_<?= $row_elemento->id_alfanumerico ?>">
                        <?php
                            $opciones = json_decode($row_elemento->detalle);
                        ?>

                        <div class="pull-right">
                            <span class="a4 editar_elemento" id="editar_<?= $row_elemento->orden ?>" title="Editar elemento"><i class="fa fa-pencil"></i></span>
                            <span class="a4 eliminar_elemento" id="eliminar_<?= $row_elemento->id_alfanumerico ?>" title="Eliminar elemento"><i class="fa fa-times"></i></span>
                        </div>


                        <p>
                            <span class="etiqueta informacion"><?= ($row_elemento->orden + 1)?></span>
                            <span><?= $row_elemento->texto ?></span>
                        </p>

                        <ol>
                            <?php foreach ($opciones as $key => $opcion) : ?>
                                <?php
                                    $clase = '';
                                    if ( $key == $row_elemento->clave ) {
                                        $clase = 'etiqueta exito';
                                    }
                                ?>  



                                <li>
                                    <span class="<?= $clase ?>" id="opcion_<?= $key . '_' . $row_elemento->id_alfanumerico ?>"><?= $opcion ?></span>
                                </li>

                            <?php endforeach ?>
                        </ol>

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