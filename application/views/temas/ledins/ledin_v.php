<?php
    $lapse_index = 3;

    //Velocidades, lapsos entre palabra y palabra en milisegundos
    $arr_lapses = array(
        1 => '2000',
        2 => '950',
        3 => '515',
        4 => '280',
        5 => '130'
    );
?>

<link href="https://fonts.googleapis.com/css?family=Merriweather&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo URL_RESOURCES ?>css/lectura_dinamica.css">

<script>
    var i = 0;  //Índice palabra
    var lapse = <?php echo $arr_lapses[$lapse_index] ?>;
    var lapse_index = <?php echo $lapse_index; ?>;
    var pending = {};
    var restart_timeout;

    $(document).ready(function(){

        $('.playing').hide();

        var palabras = $("#lectura_dinamica span");

        //Iniciar recorrido de palabras
        $('#btn_play').click(function(){
            
            $('.stopped').hide();
            $('.playing').show();

            $("#lectura_dinamica span").addClass('atenuada');
            for (let index = 0; index < palabras.length; index++)
            {
                siguiente_palabra(index);
            }

            //Al final mostrar diccionario
            restart_timeout = setTimeout(() => {
                $('.stopped').show();
                $('.playing').hide();
            }, palabras.length * lapse);

        });

        //Detener lectura dinámica
        $('.btn_stop_ledin').click(function(){
            console.log('Lectura dinámica detenida');
            for (var t in pending) if (pending.hasOwnProperty(t)) {
                clearTimeout(t);
                delete pending[t];
            }

            clearTimeout(restart_timeout);
            $('.stopped').show();
            $('.playing').hide();
        });

        //Pasar a siguiente palabra, (span)
        function siguiente_palabra(i)
        {
            t = setTimeout(() => {
                var palabra_ant = palabras[i-1];
                var palabra = palabras[i];
                var palabra_2 = palabras[i+1];
                var palabra_3 = palabras[i+2];
                console.log(i);
                $("#lectura_dinamica span").removeClass('resaltada');
                $(palabra).removeClass('atenuada', 'slow');
                $(palabra).addClass('resaltada', 'slow');
                $(palabra_2).addClass('resaltada', 'slow');
                $(palabra_3).addClass('resaltada', 'slow');
            }, i * lapse);

            pending[t] = 1; //Cagar array de eventos programados
        } 

        //Seleccionar velocidad de lectura
        $('.btn_speed').click(function(){
            $('.btn_speed').removeClass('btn-primary');
            $('.btn_speed').addClass('btn-light');
            $(this).removeClass('btn-light');
            $(this).addClass('btn-primary');
            lapse = $(this).data('lapse');
            console.log(lapse);
        });
        
        $('.con_definicion').popover({
            container: '#ledin'
        })

        $('.con_definicion').hover(function(){
            var definicion = $(this).data('content');
        });

        $('.con_definicion').click(function(){
            var definicion = $(this).data('content');
            var titulo = $(this).html();
            console.log(titulo);
            $('#definicion').html(definicion);
            $('#titulo_modal').html(titulo);
            $('#modal_definicion').modal('toggle')
        });
    });    
</script>


<div id="ledin">
    <div>
        <?php if ( ! is_null($ledin) ) { ?>
            <?php
                $elementos = json_decode($ledin->contenido_json);
            ?>
            <h2 class="text-center"><?php echo $ledin->nombre_post ?></h2>

            <?php if ( $ledin->texto_2 ) { ?>
                <img src="<?php echo URL_UPLOADS . 'lecturas_dinamicas_imagenes/' . $ledin->texto_2 ?>" alt="" width="100%" class="rounded mb-3">
            <?php } ?>

            <div class="mb-3">
                <button class="btn btn-success w4 stopped" id="btn_play">
                    Iniciar Lectura
                </button>
                <button class="btn btn-warning w4 playing btn_stop_ledin">
                    Detener
                </button>
                <div class="btn-group stopped" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary" disabled>Velocidad</button>
                    <?php foreach ( $arr_lapses as $key => $lapse ) { ?>    
                        <?php
                            $cl_lapse = $this->Pcrn->clase_activa($key, $lapse_index, 'btn-primary', 'btn-light');
                        ?>
                        <button type="button" class="btn w2 btn_speed <?php echo $cl_lapse ?>" data-lapse="<?php echo $lapse ?>">
                            <?php echo $key; ?>
                        </button>
                    <?php } ?>
                </div>
            </div>

            <?php if ( isset($elementos) ) : ?>
                <div id="lectura_diccionario" class="ledin_contenido stopped">
                    <?php echo $elementos->diccionario ?>
                </div>
                <div id="lectura_dinamica" class="ledin_contenido playing">
                    <?php echo $elementos->lectura_dinamica ?>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>

    <!-- Modal Definición -->
    <div class="modal fade" id="modal_definicion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titulo_modal">Palabra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="definicion"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>