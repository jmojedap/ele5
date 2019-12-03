<?php
    $url_background_image = $imagen['src'];
    $key_element = 0;

    //Llenar de 0 array de resultado para javascript
    $str_results = str_repeat('0,', $elementos->num_rows());
    $str_results = substr($str_results, 0,-1);
?>

<style>
    .bg{
        width: 800px;
        height: 450px;
        margin: 0 auto;
        position: relative;
        border: 1px solid #d3d3d3;
        background-repeat: no-repeat;
    }   

    .draggable{
        box-sizing: border-box; 
        min-width: 64px; 
        min-height: 24px; 
        position: absolute;
        cursor: move;
        text-align: center;
        font-family: Calibri, Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight: bold;
        overflow: hidden;
    }

    .draggable:hover{
        opacity: 0.9;
    }
</style>

<script>
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#btn_check_results').click(function(){
            check_results();
        });
    });
// Functions
//-----------------------------------------------------------------------------

    
    var qty_elements = <?php echo $elementos->num_rows() ?>;
    var qty_corrects = 0;
    var results = [<?php echo $str_results ?>];

    /*
     * Script para habilitar la funcionalidad de
       arrastrar y redimensionar los divs visuales
       sobre la imagen principal del quiz
       todo se hace por medio de JQuery UI
     */
    $(function() {
        $(".draggable").draggable({
            containment: "#quiz_container",
            scroll: false,
            stop: function() {
                $(this).removeClass('heartBeat');
                $(this).removeClass('wobble');
                $(this).removeClass('flip');

                var top_answer = $(this).data('top');
                var left_answer = $(this).data('left');
                var key = $(this).data('key');

                var this_position = $(this).offset();
                var container_position = $('#quiz_container').offset();

                var top = this_position.top - container_position.top
                var left = this_position.left - container_position.left

                var desv_top = Math.abs(top - $(this).data('top'));
                var desv_left = Math.abs(left - $(this).data('left'));
                //console.log('desv_top:' + desv_top);                
                /* console.log('container_left:' + container_position.left);
                console.log('left:' + left);
                console.log('desv_left:' + desv_left); */

                if ( desv_top < 30 && desv_left < 30 )
                {
                    $(this).css({ top: top_answer + 'px' });
                    $(this).css({ left: left_answer + 'px' });
                    
                    $(this).addClass('heartBeat');
                    results[key] = 1;
                } else {
                    $(this).addClass('wobble');
                    results[key] = 0;
                }

                check_answer();
            },
            drag: function() {
                $(this).addClass('dragging');
            }
        });
    });

    /** Verificar si las respuestas son correctas */
    function check_answer()
    {
        qty_corrects = 0;
        results.forEach(element => {
            qty_corrects += element;
        });

        if ( qty_corrects == qty_elements ) { check_results(); }

        console.log('Correctas: ' + qty_corrects);
        console.log('Resultado: ' + resultado);
    }

    function check_results()
    {
        if ( qty_corrects == qty_elements ) {
            Swal.fire(
                '¡Bien hecho!',
                'Tu respuesta es correcta',
                'success'
            );
            resultado = 1;  //Correcto
        } else {
            Swal.fire(
                'Algo no está bien',
                'Por favor, vuelve a intentarlo!',
                'info'
            );
            resultado = 0;  //Incorrecto
        }

        guardar_resultado();
    }
    
</script>

<div class="container">
    <div style="width: 800px; margin: 0 auto;">
        <h1><?php echo $head_title ?></h1>
        <p style="font-size: 1.2em;">
            <i class="fa fa-info-circle"></i>
            <?php if ( strlen($row->texto_enunciado) > 0 ) { ?>
                <?php echo $row->texto_enunciado ?>
            <?php } else { ?>
                <?php echo $row_tipo_quiz->enunciado ?>
            <?php } ?>
        </p>
    </div>
    <div id="quiz_container" class="bg mt-2" style="background-image: url('<?php echo $url_background_image ?>');">
        <?php foreach ( $elementos->result() as $row_elemento ) { ?>
            <?php
                $pos = 5 + $key_element * 5;    //5px de margen, + 5px adicionales por cada elemento
                $style = "top: {$pos}px; left: {$pos}px";
                $key_element++;
            ?>
            <img
                class="draggable animated flip"
                src="<?php echo URL_UPLOADS . 'quices/' . $row_elemento->archivo ?>"
                style="<?php echo $style ?>"
                data-top="<?php echo $row_elemento->y ?>"
                data-left="<?php echo $row_elemento->x ?>"
                data-key="<?php echo $key_element ?>"
                >
        <?php } ?>
    </div>
    <div class="text-center mt-2">
        <button class="btn btn-lg btn-success" id="btn_check_results">
            <i class="fa fa-check"></i>
            Verificar
        </button>
    </div>
</div>