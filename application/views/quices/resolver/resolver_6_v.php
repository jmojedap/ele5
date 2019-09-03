<?php
    $cant_elementos = $elementos->num_rows() - 1;
    $key_elemento = 0;
    
    //Imagen principal
    $att_img = array(
        'id' => 'imagen_quiz',
        'src' => $imagen['src'],
        'width' => '100%',
        'style' => 'position: absolute; max-width: 800px',
        'class' =>  'principal'
    );
    
    //Imagen quiz
    $att_img_elemento = array(
        'class' => 'img_elemento'
    );
    
    
    //Array elementos
    $array_elementos = array();
    foreach ( $elementos->result() as $row_elemento ) {
        $array_elementos[] = $row_elemento->orden . '|' . $row_elemento->detalle;
    }
    
    shuffle($array_elementos);
?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php URL_RESOURCES ?>js/Math.uuid.js"></script>

<script>
    //Variables
        var respuesta_arr = [];
        <?php for ($i = 0; $i <= $cant_elementos; $i++) { ?>
            respuesta_arr[<?= $i ?>] = '';
        <?php } ?>
        var respuesta = JSON.stringify(respuesta_arr);
        var clave = '<?= $row->clave ?>';
        var resultado = 0;
        var quiz_id = <?= $row->id ?>;
        var usuario_id = <?= $this->session->userdata('usuario_id') ?>
</script>

<script>
    
    $(document).ready(function(){
        
        $('#resultado_incorrecto').hide();
        $('#resultado_correcto').hide();
        $('#respuesta_quiz').val(respuesta);
        
        $('#enviar').click(function(){
            actualizar_resultado();
            guardar_resultado();
        });
    });
    
    function actualizar_resultado()
    {
        if ( respuesta === clave ) {
            resultado = 1;
            toastr['success']('¡Correcto, felicitaciones!');
        } else {
            resultado = 0;
            toastr['warning']('Incorrecto, inténtalo de nuevo');
        }
    }
    
    //Guardar resultado al resolver el quiz
    function guardar_resultado(){
        
        $.ajax({        
            type: 'POST',
            url: '<?= base_url() ?>quices/guardar_resultado',
            data: {
                usuario_id : usuario_id,
                quiz_id : quiz_id,
                resultado : resultado
            }
        });

    }
    
    
</script>

<script>
    $(function() {
        /*
         * Script para habilitar la funcionalidad de
           arrastrar y redimensionar los divs visuales
           sobre la imagen principal del quiz
           todo se hace por medio de JQuery UI
         */
        $(".draggable").draggable({
            containment: "#quiz-container", 
            scroll: false
        });
        
        $(".droppable" ).droppable({
            drop: function(ev, ui){
                index = $(this).data('orden');
                respuesta_arr[index] = ui.draggable.attr('data-orden');
                respuesta = JSON.stringify(respuesta_arr);
                respuesta = respuesta.replace(new RegExp('"', 'g'), '');    //Quitar las comillas
                
                //$('#respuesta').html(respuesta);
            },
            hoverClass : 'casilla_hover',
            tolerance: 'pointer'
        });
    });
</script>

<style>
    
    .tabla-quiz{
        width: 100%;
        border: 0px;
    }
    
    .tabla-quiz td{
        padding: 5px;
        border: 1px solid #DDD;
    }
    
    .casilla_vacia{
        width: 450px;
        height: 3em;
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        padding: 2px; 
        background: #fff; 
        border: 1px solid #CCC;
        box-shadow: inset 0 3px 8px #CCC;
        text-align: center;
        font-family: Calibri, Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        overflow: hidden;
        border-radius: 2px;
    }
    
    .casilla_hover{
        border: 1px solid #f4b904;
        background: #f4b904;
        box-shadow: inset 0 3px 8px #f4b904;
    }
    
    .draggable{
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        padding: 3px; 
        background: #fde08a; 
        border: 1px solid #f4b904;
        cursor: move;
        text-align: center;
        font-family: Calibri, Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        overflow: hidden;
        border-radius: 3px;
        display: inline-block;
    }
    
    img.img_elemento{
        max-width: 150px;
    }
</style>

<?php if ( strlen($imagen['src']) > 0 ){ ?>
    <div class="div2" style="text-align: center;">
        <?php $att_img['src'] = $imagen['src'] ?>
        <?= img($att_img) ?>
    </div>
<?php } ?>


<div class="quiz-container">
    
    <div style="position: relative" class="div3">
        <p>Arrastra las etiquetas a su posición correcta</p>
        <?php foreach ($array_elementos as $str_elemento) : ?>
            <?php
                $array_elemento = explode('|', $str_elemento);
                $orden = $array_elemento[0];
                $detalle = $array_elemento[1];     
            ?>
            <div class="draggable" data-orden="<?= $orden ?>">
                <?= $detalle ?>
            </div>
        <?php endforeach ?>
    </div>
    
    <table class="tabla-quiz">
        <tbody>
            <?php foreach ($elementos->result() as $row_elemento) : ?>
                <tr>
                    <?php
                        $primera_columna = $row_elemento->texto;
                        if ( strlen($row_elemento->archivo) > 0 ) {
                            //$src = RUTA_UPLOADS . 'quices/' . $row_elemento->archivo;
                            $att_img_elemento['src'] = RUTA_UPLOADS . 'quices/' . $row_elemento->archivo;
                            $primera_columna = img($att_img_elemento);
                        }
                    ?>
                    <td><?= $primera_columna ?></td>
                    <td class="droppable casilla_vacia" data-orden="<?= $row_elemento->orden ?>"></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    
</div>

<p id="respuesta">
    
</p>
