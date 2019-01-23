<?php
    $cant_elementos = $elementos->num_rows() - 1;
    $key_elemento = 0;
    
    //Imagen principal
        $att_img = array(
            'id' => 'imagen_quiz',
            'src' => $imagen['src'],
            'width' => '100%',
            'style' => 'position: absolute; max-width: 800px'
        );
    
    //Imágenes elementos
        $carpeta_quices = base_url() . RUTA_UPLOADS . 'quices/';
    
    //Array elementos
        $array_elementos = array();
        foreach ( $elementos->result() as $row_elemento ) {
            $array_elementos[] = $row_elemento->orden . '|' . $row_elemento->archivo . '|' . $row_elemento->y . '|' . $row_elemento->x;
        }

        shuffle($array_elementos);
?>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= base_url('js/Math.uuid.js') ?>"></script>

<script>
    //Variables
        var respuesta_arr = [];
        var arr_desv_top = [];
        var arr_desv_left = [];
        <?php for ($i = 0; $i <= $cant_elementos; $i++) { ?>
            respuesta_arr[<?= $i ?>] = '';
            arr_desv_top[<?= $i ?>] = 50;   //Valor por defecto
            arr_desv_left[<?= $i ?>] = 50;   //Valor por defecto
        <?php } ?>
        var respuesta = JSON.stringify(respuesta_arr);
        var clave = '<?= $row->clave ?>';
        var resultado = 0;
        var quiz_id = <?= $row->id ?>;
        var usuario_id = <?= $this->session->userdata('usuario_id') ?>;
        var ajuste_top = 610;       //Ajuste de coordenada Y, con la imagen de fondo
        var posision_top = 0;
        var posision_left = 0;
        var desv_top_max = 100;     //Valor inicial de desviación top máxima
        var desv_left_max = 100;    //Valor inicial de desviación left máxima
        var i = 0;                  //Índice de los elementos
        var tolerancia = 25;        //Número de pixeles de desviación que se acepta tomar un resultado como correcto
</script>

<script>
    
    $(document).ready(function()
    {
        $('#resultado_incorrecto').hide();
        $('#resultado_correcto').hide();
        $('#respuesta_quiz').val(respuesta);
        
        $('#enviar').click(function(){
            actualizar_resultado();
            guardar_resultado();
        });
    });
    
// FUNCIONES
//-----------------------------------------------------------------------------
    
    function actualizar_resultado()
    {
        var condiciones = 0;
        if ( desv_top_max < tolerancia ) { condiciones++; }     //Si la desviación máxima es menor a la permitida
        if ( desv_left_max < tolerancia ) { condiciones++; }    //Si la desviación máxima es menor a la permitida
        
        //Se deben cumplir las dos condiciones
        if ( condiciones === 2 ) {
            resultado = 1;
            toastr['success']('¡Correcto, felicitaciones!');
        } else {
            resultado = 0;
            toastr['warning']('Incorrecto, inténtalo de nuevo');
        }
        
    }
    
    //Guardar resultado al resolver el quiz
    function guardar_resultado()
    {
        
        $.ajax({        
            type: 'POST',
            url: '<?= base_url('quices/guardar_resultado') ?>',
            data: {
                usuario_id : usuario_id,
                quiz_id : quiz_id,
                resultado : resultado
            }
        });

    }

    /*
     * Script para habilitar la funcionalidad de
       arrastrar y redimensionar los divs visuales
       sobre la imagen principal del quiz
       todo se hace por medio de JQuery UI
     */
    $(function() {
        $(".draggable").draggable({
            containment: "#quiz-container", 
            scroll: false,
            stop: function() {
                // Show dropped position.
                var Stoppos = $(this).position();
                
                posision_top = Stoppos.top + ajuste_top;
                posision_left = Stoppos.left;
                
                //Calcular desviación para elemento i
                i = $(this).data('orden');
                arr_desv_top[i] = Math.abs(posision_top - $(this).data('top'));
                arr_desv_left[i] = Math.abs(posision_left - $(this).data('left'));
                
                calc_desv_max();    //Calcular la desviación máxima
            }
        });
    });
    
    //Calcular desviaciones máximas, en top y left
    function calc_desv_max()
    {
        desv_top_max = Math.max(...arr_desv_top);  //Ver spread operator
        desv_left_max = Math.max(...arr_desv_left);  //Ver spread operator
    }    
</script>

<style>
    .casilla_vacia{
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        position: absolute;
        padding: 2px; 
        text-align: center;
        font-family: Calibri, Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        overflow: hidden;
        border-radius: 5px;   
    }
    
    .draggable{
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        /*position: absolute; */
        cursor: move;
        text-align: center;
        font-family: Calibri, Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        overflow: hidden;
        border-radius: 2px;
        display: inline-block;
    }
</style>

<p id="respuesta"></p>
<p id="stop_result"></p>

<div class="quiz-container">

    <div id="div-imagen" style="width: 804px; height: 604px; border: 1px solid #DDD; padding: 2px; position: relative;" class="div2">
        <?php if ( strlen($imagen['src']) > 0 ){ ?>
            <?php $att_img['src'] = $imagen['src'] ?>
            <?= img($att_img) ?>
        <?php } ?>
        
        <?php foreach ($elementos->result() as $row_elemento) : ?>
            <?php
                $width = number_format($row_elemento->ancho * 0.7, 0);
                $heigth = number_format($row_elemento->alto * 0.7, 0);
                $left = $row_elemento->x + number_format($row_elemento->ancho * 0.15,0);
                $top = $row_elemento->y + number_format($row_elemento->alto * 0.15,0);
                $line_height = $row_elemento->alto * 0.9;
                
                $style = "left: {$left}px; top: {$top}px; width: {$width}px; height: {$heigth}px; line-height: {$line_height}px";
            ?>
            
            <div id="casilla_<?= $row_elemento->id_alfanumerico ?>" class="droppable casilla_vacia" style="<?= $style ?>" data-orden="<?= $row_elemento->orden ?>">
            </div>
        <?php endforeach ?>
    </div>
    
    <div style="position: relative">
        <p>Arrastra las imágenes a su posición correcta</p>
        <?php foreach ($array_elementos as $str_elemento) : ?>
            <?php
                $array_elemento = explode('|', $str_elemento);
                $orden = $array_elemento[0];
                $archivo = $array_elemento[1];     
                $att_img_elemento['src'] = $carpeta_quices . $archivo;
                $att_img_elemento['style'] = 'opacity: 0.8;';
            ?>
            <div class="draggable" data-orden="<?= $orden ?>" data-top="<?= $array_elemento[2] ?>" data-left="<?= $array_elemento[3] ?>">
                <?= img($att_img_elemento) ?>
            </div>
        <?php endforeach ?>
    </div>
</div>

