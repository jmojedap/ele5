<?php $this->load->view('assets/sweetalert2') ?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<link href="<?php echo URL_RESOURCES . 'css/animate.css' ?>" rel="stylesheet">

<?php
    $url_background_image = URL_IMG . 'demo/evidencias/fondo_5.png';
    $elements = array(
        array('archivo' => 'elemento_1.png', 'y' => '25', 'x' => '275', 'status' => 0),
        array('archivo' => 'elemento_2.png', 'y' => '310', 'x' => '230', 'status' => 0),
        array('archivo' => 'elemento_3.png', 'y' => '45', 'x' => '509', 'status' => 0),
        array('archivo' => 'elemento_4.png', 'y' => '166', 'x' => '147', 'status' => 0),
        array('archivo' => 'elemento_5.png', 'y' => '246', 'x' => '432', 'status' => 0),
    );
?>

<style>
    .bg{
        width: 800px;
        height: 450px;
        margin: 0 auto;
        position: relative;
        border: 1px solid #d3d3d3;
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
    $(document).ready(function(){
        $('#btn_check_results').click(function(){
            check_results();
        });
    });


// Functions
//-----------------------------------------------------------------------------

    var result = 0;
    var qty_corrects = 0;
    var results = [0,0,0,0,0];

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

        if ( qty_corrects == 5 ) { check_results(); }

        console.log('Correctas: ' + qty_corrects);
        console.log('Resultado: ' + result);
    }

    function check_results()
    {
        if ( qty_corrects == 5 ) {
            Swal.fire(
                '¡Bien hecho!',
                'Tu respuesta es correcta',
                'success'
            );
        } else {
            Swal.fire(
                'Algo no está bien',
                'Por favor, vuelve a intentarlo!',
                'info'
            );
        }
    }
    
</script>

<div class="container">
    <div style="width: 800px; margin: 0 auto;">
        <h3>Evidencias Tipo M2</h3>
        <p class="animated bounceInRight delay-2s" style="font-size: 1.2em;">Ubica las banderas en la casilla del país correspondiente</p>
    </div>
    <div id="quiz_container" class="bg mt-2" style="background-image: url('<?php echo $url_background_image ?>');">
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
    <div class="text-center mt-2">
        <button class="btn btn-lg btn-success" id="btn_check_results">
            <i class="fa fa-check"></i>
            Verificar
        </button>
    </div>
    <div class="card my-2" style="width: 900px; margin: 0 auto;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Demo Evidencia Tipo M2</h3>
                    <p>
                        Las evidencias tipo M consisten en ubicar correctamente unas imágenes sobre un plano.
                        Este es el demo de una versión mejorada de este tipo de evidencias. Tiene las siguientes <b class="text-success">mejoras</b> respecto a la versión original:
                    </p>
                    <ul>
                        <li>Tienen un tamaño fijo de fondo de 800 x 450px.</li>
                        <li>El tamaño del fondo podrá adaptarse al tamaño de la pantalla.</li>
                        <li>Los elementos que deben ubicarse, aparecen ya dentro del plano de trabajo, y no pueden salir de ahí.</li>
                        <li>Los elementos tienen una animación inicial para mostrarse y resaltarse ante el usuario.</li>
                        <li>Los elementos aparecen superpuestos al inicio en la esquina superior izquierda.</li>
                        <li>
                            <b class="text-success">Auto Verificación</b>
                            Después de cada movimiento de un elemento la herramienta verifica si la respuesta es correcta o no. De manera que al completar
                            correctamente la evidencia, se muestra automáticamente el aviso de resultado correcto, sin necesidad de presionar el botón [Verificar].
                        </li>
                        <li>
                            <b class="text-success">Efecto SNAP</b>
                            Cuando un elemento es arrastrado a 30px o menos de su ubiciación correcta, este se mueve automáticamente
                            o "magnéticamente" a su posición exacta.
                        </li>
                        <li>
                            <b class="text-success">Efecto LATIDO</b>
                            Cuando un elemento es arrastrado a su ubicación correcta muestra una animación de látido resaltando
                            que se ubicó bien.
                        </li>
                        <li>
                            <b class="text-danger">Efecto WOOBLE</b>
                            Cuando un elemento se arrastra a una ubicación incorrecta, muestra una animación de sacudidido hacia locale_set_default
                            lados, como negación, indicando que la posición es incorrecta.
                        </li>
                        <li>
                            <b class="text-success">Librerías SweetAlert: </b>
                            Al hacer clic en el botó [Verificar] se muestra un aviso en una ventana modal haciendo uso de la librería SweetAlert, con una animación
                            y visualización más moderna, atractiva y agradable.
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Propuesta</h4>
                    <p>
                        La propuesta de implementar este desarrollo tiene dos puntos:
                    </p>
                    <ol>
                        <li>Implementar estas mejoras en las evidencias actuales tipo M. Esto se haría con una <b>conversión masiva</b> de las <b class="text-success">109</b>
                            evidencias existente desde el tipo M a tipo M2.
                        </li>
                        <li>
                            Reemplazar la herramienta "Contructora" de las evidencias Tipo M por el constructor de evidencias Tipo M2. Incluirá el manual y las respectivas
                            recomendaciones para la óptima construcción y funcionamiento de estas evidencias.
                        </li>
                    </ol>

                    <h4>Precio</h4>
                    <p>
                        El precio por este desarrollo, para evidencias tipo M2 con lo mencionado anteriormente tiene un precio de <b class="text-success" style="font-size: 1.5em;">$1.270.000</b>.
                        El tiempo estimado de desarrollo e implementación es de <b class="text-info">1.5 semanas</b>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>