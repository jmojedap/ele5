<?php
    $url_background_image = $imagen['src'];
    $key_element = 0;

    //Llenar de 0 array de resultado para javascript
    $str_results = str_repeat('0,', $elementos->num_rows());
    $str_results = substr($str_results, 0,-1);
?>

<script>
//-----------------------------------------------------------------------------
    /**
    Script para habilitar la funcionalidad de arrastrar y redimensionar los divs visuales 
    sobre la imagen principal del quiz todo se hace por medio de JQuery UI
     */
    $(function() {
        $(".draggable").draggable({
            containment: "#quiz_container",
            scroll: false,
            stop: function() {
                //Quitar clases de animación
                $(this).removeClass('animate__heartBeat');
                $(this).removeClass('animate__wobble');
                $(this).removeClass('animate__swing');

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

                    console.log('ubicar')
                    
                    $(this).addClass('animate__heartBeat');
                    $(this).addClass('correct');
                    resolverQuiz.resultados[key] = 1;
                    resolverQuiz.play_audio('audio_answer');
                } else {
                    $(this).addClass('animate__wobble');
                    $(this).removeClass('correct');
                    resolverQuiz.resultados[key] = 0;
                    resolverQuiz.play_audio('audio_wrong');
                }

                resolverQuiz.verificar_respuesta(); //Revisar respuesta
            }
        });
    });
</script>

<div id="resolverQuiz">    
    <div id="quiz_container" class="mb-2">
        <img src="<?= $url_background_image ?>" alt="Imagen de fondo" class="img_bg">
        <?php foreach ( $elementos->result() as $row_elemento ) { ?>
            <?php
                $pos = 10 + $key_element * 7;           //5px de margen, + 5px adicionales por cada elemento
                $top = random_int(50, 100);
                $left = random_int(1, 100);
                $z_index = random_int(10, 100);         //Para no mostrar en orden de construcción
                $style = "top: {$top}px; left: {$left}px; z-index: {$z_index}";  //Ubicación inicial
                $key_element++; //Siguiente elemento
            ?>
            <img
                class="draggable animate__animated animate__swing"
                src="<?= URL_UPLOADS . 'quices/' . $row_elemento->archivo ?>"
                style="<?= $style ?>"
                data-top="<?= $row_elemento->y ?>"
                data-left="<?= $row_elemento->x ?>"
                data-key="<?= $key_element ?>"
                >
        <?php } ?>
    </div>
</div>

<script>
var resolverQuiz = new Vue({
    el: '#resolverQuiz',
    data: {
        quiz: <?= json_encode($row) ?>,
        resultado: 0,
        usuario_id: '<?= $this->session->userdata('user_id') ?>',
        cant_elementos: <?= $elementos->num_rows() ?>,
        cant_correctas: 0,
        resultados: [<?= $str_results ?>],
        loading: false,
    },
    methods: {
        guardar_resultado: function(){
            this.loading = true
            var form_data = new FormData
            form_data.append('resultado', this.resultado)
            form_data.append('quiz_id', this.quiz.id)
            form_data.append('usuario_id', this.usuario_id)
            axios.post(url_api + 'quices/guardar_resultado/', form_data)
            .then(response => {
                console.log('ua_id:' + response.data);
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        verificar_respuesta: function(){
            this.cant_correctas = 0;
            this.resultados.forEach(element => {
                this.cant_correctas += element;
            });

            if ( this.cant_correctas == this.cant_elementos ) this.verificar_resultados()

            console.log('Correctas: ' + this.cant_correctas);
            console.log('Resultado: ' + this.resultado);
        },
        verificar_resultados: function(){
            if ( this.cant_correctas == this.cant_elementos ) {
                Swal.fire('¡Bien hecho!', 'Tu respuesta es correcta', 'success')
                this.resultado = 1;  //Correcto
                this.play_audio('audio_win')
            } else {
                Swal.fire('Algo no está bien', 'Por favor, vuelve a intentarlo!', 'warning')
                this.resultado = 0;  //Incorrecto
                this.play_audio('audio_game_over')
            }

            this.guardar_resultado();
        },
        play_audio: function(audio_id){
            var audio = document.getElementById(audio_id);
            audio.play();
        },
    }
})
</script>