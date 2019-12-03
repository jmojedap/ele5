<?php
    $url_background_image = $imagen['src'];
    $key_element = 0;

    //Llenar de 0 array de resultado para javascript
    $str_results = str_repeat('0,', $elementos->num_rows());
    $str_results = substr($str_results, 0,-1);
?>

<script>
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#btn_check_results').click(function(){
            check_results();
        });
    });

// Variables
//-----------------------------------------------------------------------------    
    var qty_elements = <?php echo $elementos->num_rows() ?>;
    var qty_corrects = 0;
    var results = [<?php echo $str_results ?>];

// Functions

//-----------------------------------------------------------------------------
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
                //Quitar clases de animación
                $(this).removeClass('heartBeat');
                $(this).removeClass('wobble');
                $(this).removeClass('flip');

                //Identicar respuestas correctas desde data del elemento
                var top_answer = $(this).data('top');
                var left_answer = $(this).data('left');
                var key = $(this).data('key');

                var this_position = $(this).offset();
                var container_position = $('#quiz_container').offset();

                //Posisiones relativas
                var top = this_position.top - container_position.top
                var left = this_position.left - container_position.left

                //Calcular desviación
                var desv_top = Math.abs(top - $(this).data('top'));
                var desv_left = Math.abs(left - $(this).data('left'));

                //Si es menor a 30px se considera correcta
                if ( desv_top < 30 && desv_left < 30 )
                {
                    $(this).css({ top: top_answer + 'px' });
                    $(this).css({ left: left_answer + 'px' });
                    
                    $(this).addClass('heartBeat');
                    $(this).addClass('correct');
                    results[key] = 1;
                } else {
                    $(this).addClass('wobble');
                    $(this).removeClass('correct');
                    results[key] = 0;
                }

                check_answer(); //Revisar respuesta
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

    /** Verificar y mostrar resultado, enviar respuesta */
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

<div id="quiz_container" class="mb-2">
    <img src="<?php echo $url_background_image ?>" alt="Imagen de fondo" class="img_bg">
    <?php foreach ( $elementos->result() as $row_elemento ) { ?>
        <?php
            $pos = 10 + $key_element * 7;           //5px de margen, + 5px adicionales por cada elemento
            $z_index = random_int(10, 100);         //Para no mostrar en orden de construcción
            $style = "top: {$pos}px; left: {$pos}px; z-index: {$z_index}";  //Ubicación inicial
            $key_element++; //Siguiente elemento
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