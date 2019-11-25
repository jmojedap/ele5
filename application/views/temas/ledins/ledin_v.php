<link href="https://fonts.googleapis.com/css?family=Merriweather&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo URL_RESOURCES ?>css/lectura_dinamica.css">

<script>
    var i = 0;
    var lapse = 150;

    $(document).ready(function(){

        var palabras = $("#lectura_dinamica span");

        $('#btn_play').click(function(){
            
            $('#lectura_dinamica').show();
            $('#lectura_diccionario').hide();
            $('#btn_play').hide();

            $("#lectura_dinamica span").addClass('atenuada');
            //$("#texto_prueba span").addClass('palabra');
            for (let index = 0; index < palabras.length; index++)
            {
                siguiente_palabra(index);
            }

            setTimeout(() => {
                $('#lectura_dinamica').hide();
                $('#lectura_diccionario').show();
                $('#btn_play').show();
            }, palabras.length * lapse);

        });

        function siguiente_palabra(i)
        {
            setTimeout(() => {
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
        }
        

        $(function () {
            $('.con_definicion').popover({
                container: '#ledin'
            })
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
    <div id="ledin_contenido">
        <?php if ( ! is_null($ledin) ) { ?>
            <?php
                $elementos = json_decode($ledin->contenido_json);
            ?>
            <h4 class="card-title"><?php echo $ledin->nombre_post ?></h4>
            <img src="<?php echo URL_UPLOADS . 'lecturas_dinamicas_imagenes/' . $ledin->texto_2 ?>" alt="" width="100%" class="rounded mb-3">
            <div id="lectura_diccionario">
                <?php echo $elementos->diccionario ?>
            </div>
            <div id="lectura_dinamica" style="display: none;">
                <?php echo $elementos->lectura_dinamica ?>
            </div>
        <?php } ?>
    </div>
    <button class="btn btn-success mt-2" id="btn_play">
        <i class="fa fa-play"></i>
        Lectura dinámica
    </button>

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