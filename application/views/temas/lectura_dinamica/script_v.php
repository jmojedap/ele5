<?php
    $lapse_index = 3;
?>

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
            new bootstrap.Modal($('#definicionModal')).show();
        });
    });    
</script>