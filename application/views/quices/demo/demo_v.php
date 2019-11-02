<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<link href="<?php echo URL_RESOURCES . 'css/animate.css' ?>" rel="stylesheet">

<?php
    $url_background_image = URL_IMG . 'demo/evidencias/fondo_3.png';
    $elements = array(
        array('archivo' => 'elemento_1.png', 'y' => '25', 'x' => '275', 'status' => 0),
        array('archivo' => 'elemento_2.png', 'y' => '310', 'x' => '230', 'status' => 0),
        array('archivo' => 'elemento_3.png', 'y' => '45', 'x' => '509', 'status' => 0),
        array('archivo' => 'elemento_4.png', 'y' => '166', 'x' => '147', 'status' => 0),
        array('archivo' => 'elemento_5.png', 'y' => '246', 'x' => '452', 'status' => 0),
    );
?>

<style>
    .bg{
        width: 800px;
        height: 450px;
        margin: 0 auto;
        position: relative;
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

    /*.dragging{
        border: 1px solid #64b5f6;
        -webkit-box-shadow: 5px 5px 5px 0px rgba(194,194,194,1);
        -moz-box-shadow: 5px 5px 5px 0px rgba(194,194,194,1);
        box-shadow: 5px 5px 5px 0px rgba(194,194,194,1);
    }*/
</style>
<script>

    var result = 0;
    var qty_right = 0;
    var status = [<?php foreach ( $elements as $key => $element ) { ?><?php echo 0 ?>,<?php } ?>];

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
                console.log(key);

                var this_position = $(this).offset();
                var container_position = $('#quiz_container').offset();

                var top = this_position.top - container_position.top
                var left = this_position.left - container_position.left

                /*console.log('top: ' + top);
                console.log('left: ' + left);*/

                //status[key] = 1;
                console.log(status[key]);

                

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
                    status[key] = 1;
                } else {
                    $(this).addClass('wobble');
                    status[key] = 0;
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
        var sum = 0;
        for (let index = 0; index < status.length; index++)
        {
            const value = status[index];
            console.log(index);
            if ( value == 0 ) { result = 0; }
            sum += value;
        }

        qty_right = sum;

        console.log('Correctas: ' + sum);
        console.log('Resultado: ' + result);
    }
    
</script>

<div class="container">
    <h1 class="">Example</h1>
    <p>Y elementos adicionales que pudieran existir</p>
    <div id="quiz_container" class="bg" style="background-image: url('<?php echo $url_background_image ?>');">
        <?php foreach ( $elements as $key => $element ) { ?>
            <?php
                $pos = 5 + $key * 5;
                $style = "top: {$pos}px; left: {$pos}px";
            ?>
            <img
                class="draggable animated flip"
                src="<?php echo URL_IMG . 'demo/evidencias/' . $element['archivo'] ?>"
                style="<?php echo $style ?>"
                data-top="<?php echo $element['y'] ?>"
                data-left="<?php echo $element['x'] ?>"
                data-key="<?php echo $key ?>"
                >
        <?php } ?>
    </div>
</div>