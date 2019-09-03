<?php
//Formulario
    $att_detalle = array(
        'name' => 'detalle',
        'id' => 'detalle',
        'class' => 'form-control',
        'placeholder' => 'Escriba la palabra correcta'
    );

    $att_texto = array(
        'name' => 'texto',
        'id' => 'texto',
        'rows' => 4,
        'placeholder' => 'Parrafo con casilla para completar, use #casilla'
    );

//Imagen
    $att_img = array(
        'id' => 'imagen_quiz',
        'src' => $imagen['src'],
        'width' => '100%',
        'style' => 'position: absolute; max-width: 800px'
    );
?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo URL_RESOURCES ?>js/Math.uuid.js"></script>

<script>
    //Variables
    var id_alfanumerico = Math.uuid(16, 16);
    var quiz_id = <?= $row->id ?>;
    var tipo_id = 4;    //Tipo de elemento, campo para los quices i
    var orden = <?= $row->cant_elementos ?>;
    var texto = '';
    var detalle = '';
    var x = 0;
    var y = 0;
    var alto = 30;
    var ancho = 100;
    
    var cant_elementos = <?= $row->cant_elementos ?>;
    var arr_elementos = JSON.parse('<?= $arr_elementos ?>');
</script>

<script>

    $(document).ready(function() {

        //Botón guardar_elemento
        $('#guardar_elemento').click(function(){
            cargar_variables();
            guardar_elemento();
        });
        
        //Botón eliminar
        $('.eliminar_elemento').click(function(){
            id_alfanumerico = $(this).attr('id').substring(9);  //Quitar caracteres de "eliminar_"
            eliminar_elemento(id_alfanumerico);
            $('#elemento_' + id_alfanumerico).hide('slow');
            $('#draggable_' + id_alfanumerico).hide('slow');    //Eliminar caja en la imagen
        });
        
        //Botón editar
        $('.editar_elemento').click(function(){
            orden = $(this).attr('id').substring(7);  //Quitar caracteres de "editar_"
            id_alfanumerico = arr_elementos[orden]['id_alfanumerico'];
            cargar_formulario();
            $('#guardar_elemento').html('Actualizar');
            $('#detalle').focus();
        });
        
        //Botón [Nuevo]
        $('#nuevo_elemento').click(function(){
            id_alfanumerico = Math.uuid(16, 16);
            orden = cant_elementos;
            limpiar_formulario();
            $('#guardar_elemento').html('Agregar');
            $('#detalle').focus();
        });


        /*
         * Script para habilitar la funcionalidad de
           arrastrar y redimensionar los divs visuales
           sobre la imagen principal del quiz
           todo se hace por medio de JQuery UI
         */
        $(".draggable").draggable({
            containment: "#quiz-container", 
            scroll: false,
            stop: function(e){
                cargar_variables_pos(e);
                guardar_elemento_pos();
            }
        }).resizable({
            containment: '#quiz-container',
            handles: 'e, s',
            stop: function(e){
                $(e.target).css('line-height', $(e.target).innerHeight() + 'px');
                
                cargar_variables_pos(e);
                guardar_elemento_pos();
            }
            
        });

    });

</script>

<script>

    //Guardar en la tabla quiz_elemento,
    //Incluye insertar y actualizar, dependiendo del id_alfanumerico
    function guardar_elemento()
    {
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
                clave : orden,
                x : x,
                y : y,
                alto : alto,
                ancho : ancho
            },
            success: function(){    //Actualizar la ventana
                window.location = '<?= base_url() ?>quices/construir/' + quiz_id;
            }
        });
    }

    //Guardar en la tabla quiz_elemento, variables de posicionamiento y tamaño
    //Incluye insertar y actualizar, dependiendo del id_alfanumerico
    function guardar_elemento_pos()
    {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>quices/guardar_elemento_pos',
            data: {
                id_alfanumerico : id_alfanumerico,
                quiz_id : quiz_id,
                x : x,
                y : y,
                alto : alto,
                ancho : ancho
            }
        });
     
        reiniciar_variables();  //Para un nuevo elemento
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
        $('#detalle').val('');
        $('#texto').val('');
    }

    function cargar_formulario()
    {
        $('#detalle').val(arr_elementos[orden]['detalle']);
        $('#texto').val(arr_elementos[orden]['texto']);
        x = arr_elementos[orden]['x'];
        y = arr_elementos[orden]['y'];
        alto = arr_elementos[orden]['alto'];
        ancho = arr_elementos[orden]['ancho'];
    }

    function cargar_variables()
    {
        texto = $('#texto').val();
        detalle = $('#detalle').val();
    }
    
    function cargar_variables_pos(e)
    {
        id_alfanumerico = $(e.target).data('id_alfanumerico');
        x = $(e.target).position().left;
        y = $(e.target).position().top;
        ancho = $(e.target).css('width').replace('px', '');
        alto = $(e.target).css('height').replace('px', '');
    }
    
    //Alistamiento para agregar un nuevo elemento
    function reiniciar_variables()
    {
        id_alfanumerico = Math.uuid(16, 16);
        texto = '';
        detalle = '';
        x = 0;
        y = 0;
        alto = 30;
        ancho = 100;
    }

</script>

<style>
    .draggable{
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        position: absolute; 
        padding: 2px; 
        background: #fcd04f; 
        border: 1px solid #999;
        cursor: move;
        text-align: center;
        font-family: Calibri, Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        overflow: hidden;
        border-radius: 2px;
    }
</style>

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

                        <?php if (strlen($imagen['src']) == 0) { ?>
                            <?php //Si no tiene imagen se muestra la opción de párrafo ?>
                            <tr>
                                <td>Texto</td>
                                <td><?= form_textarea($att_texto) ?></td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td></td>
                            <td>
                                <span class="btn btn-primary" id="guardar_elemento">Agregar</span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col col-md-8">
        <?php if (strlen($imagen['src']) == 0) { ?>
            <?= $this->load->view('quices/construir/form_imagen_v') ?>
        <?php } else { ?>
            <?= $this->Pcrn->anchor_confirm("quices/eliminar_archivo/{$quiz_id}/{$imagenes->row()->id_alfanumerico}", 'Eliminar', 'class="btn btn-warning" title="Eliminar imagen"', '¿Confirma la eliminación de esta imagen?') ?>
                <div id="quiz-container" style="width: 804px; height: 604px; border: 1px solid #DDD; padding: 2px; position: relative;" class="sep2">
                    <?= img($att_img) ?>

                    <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                        <?php
                            $line_height = $row_elemento->alto * 0.9;
                            $style = "left: {$row_elemento->x}px; top: {$row_elemento->y}px; width: {$row_elemento->ancho}px; height: {$row_elemento->alto}px; line-height: {$line_height}px";
                        ?>
                        <div id="draggable_<?= $row_elemento->id_alfanumerico ?>" class="draggable" style="<?= $style ?>" data-id_alfanumerico="<?= $row_elemento->id_alfanumerico ?>">
                            <?= $row_elemento->detalle ?>
                        </div>
                    <?php endforeach ?>

                </div>

        <?php } ?>
        
        <table class="table table-default bg-blanco">
            <thead>
                <th width="50px">Orden</th>
                <th>Detalle</th>
                <th>Texto</th>
                <th width="66px"></th>
            </thead>

            <tbody>
                <?php foreach ($elementos_quiz->result() as $row_elemento) : ?>
                    <tr id="elemento_<?= $row_elemento->id_alfanumerico ?>">
                        <td><span class="etiqueta informacion"><?= ($row_elemento->orden + 1) ?></span></td>
                        <td><?= $row_elemento->detalle ?></td>
                        <td><?= $row_elemento->texto ?></td>
                        <td>
                            <span class="a4 editar_elemento" id="editar_<?= $row_elemento->orden ?>"><i class="fa fa-pencil"></i></span>
                            <span class="a4 eliminar_elemento" id="eliminar_<?= $row_elemento->id_alfanumerico ?>"><i class="fa fa-times"></i></span>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <div class="sep2" style="">
            <span class="btn btn-info" id="nuevo_elemento">Nuevo</span>
        </div>
    </div>
</div>