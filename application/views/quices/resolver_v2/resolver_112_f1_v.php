<?php
    $elementos_preparados = array();
    $key = 0;
    
    foreach ($elementos->result() as $row_elemento)
    {
        $elemento['id'] = $row_elemento->id;

        $json_respuestas_correctas = '[' . $row_elemento->detalle . ']';
        $respuestas_correctas = json_decode($json_respuestas_correctas);

        //Ancho casilla
        $max_largo = 0;
        foreach ($respuestas_correctas as $respuesta_correcta) {
            if ( strlen($respuesta_correcta) > $max_largo ) $max_largo = strlen($respuesta_correcta);
        }
        
        $elemento['ancho'] =  intval(10 * $max_largo + 40 ) . 'px';
        
        $casilla = '<div class="contenedor_casilla animate__animated animate__slideInDown" id="casilla_' . $key . '">';
            $casilla .= '<input type="text" ';
                $casilla .= 'class="casilla_quiz"';
                $casilla .= 'style="width: ' . $elemento['ancho'] . '; min-width: 75px;" ';
                $casilla .= 'v-on:change="verificar_respuesta(' . $key . ')" ';
                $casilla .= 'v-bind:class="{\'correcto\': elementos[' . $key . '].resultado == 1, \'incorrecto\': elementos[' . $key . '].resultado == 0 }" ';
                $casilla .= 'v-model="elementos[' . $key . '].respuesta" ';
            $casilla .= '>';
            $casilla .= '<i class="icono_resultado fa fa-check text-success" v-show="elementos[' . $key . '].resultado == 1"></i>';
            $casilla .= '<i class="icono_resultado fa fa-times text-danger" v-show="elementos[' . $key . '].resultado == 0"></i>';
        $casilla .= '</div>';
        
        $elemento['html'] = str_replace('#casilla', $casilla, $row_elemento->texto);
        $elemento['respuesta'] = '';    //Respuesta inicial
        $elemento['respuestas_correctas'] = $respuestas_correctas;
        $elemento['resultado'] = -1;    //Sin responder

        $elementos_preparados[] = $elemento;

        $key++;
    }
?>

<div id="resolverQuiz" class="p-3">
    <?php if ( count($imagen) > 0 ) : ?>
        <div class="text-center mb-2">
            <img src="<?= $imagen['src'] ?>" class="rounded" alt="Imagen principal evidencia de aprendizaje">
        </div>
    <?php endif; ?>
    <?php foreach ( $elementos_preparados as $elemento ) : ?>
        <div class="card" style="margin-bottom: 0.5em;">
            <div class="card-body">
                <?= $elemento['html'] ?>
            </div>
        </div>
        <?php if ( $this->session->userdata('role') <= 1 ) : ?>
            <div class="alert alert-info mt-2 d-none">
                <strong>Respuestas correctas:</strong>
                <?php foreach ( $elemento['respuestas_correctas'] as $respuesta_correcta ) : ?>
                    <?= $respuesta_correcta ?> &middot;
                <?php endforeach ?>
                <br>
                <small>(Solo visible para usuarios internos)</small>
            </div>
        <?php endif; ?>
    <?php endforeach ?>
</div>

<script>
var resolverQuiz = new Vue({
    el: '#resolverQuiz',
    data: {
        quiz: <?= json_encode($row) ?>,
        resultado: 0,
        usuario_id: '<?= $this->session->userdata('user_id') ?>',
        elementos: <?= json_encode($elementos_preparados) ?>,
        loading: false,
    },
    methods: {
        verificar_respuesta: function(key){

            var respuesta_usuario = this.elementos[key].respuesta.toString().trim().toLowerCase()
            var respuestas_correctas = this.elementos[key].respuestas_correctas
            var respuestas_sin_espacios = respuestas_correctas.map(function(resp){
                return resp.toString().trim().toLowerCase()
            })
            
            //Verificar si está dentro de las posibles respuestas
            if ( respuestas_sin_espacios.includes(respuesta_usuario) ) {
                this.elementos[key].resultado = 1
                $('#casilla_' + key).removeClass('animate__slideInDown')
                $('#casilla_' + key).removeClass('animate__shakeX')
                $('#casilla_' + key).addClass('animate__pulse')
                this.play_audio('audio_answer')
            } else {
                $('#casilla_' + key).removeClass('animate__shakeX')
                $('#casilla_' + key).removeClass('animate__slideInDown')
                $('#casilla_' + key).removeClass('animate__pulse')
                $('#casilla_' + key).addClass('animate__shakeX')
                this.elementos[key].resultado = 0
                this.play_audio('audio_wrong')
            }

            //Respuesta vacía
            if ( respuesta_usuario.length == 0 ) this.elementos[key].resultado = -1

            //Verificación previa
            this.verificar_automatico()
        },
        verificar_automatico: function(){
            var elementos_correctos = this.elementos.filter(elemento => elemento.resultado == 1)
            var cant_incorrectos = this.elementos.length - elementos_correctos.length

            //Si ya todos están correctos
            if ( cant_incorrectos == 0 ) this.verificar_resultados()
        },
        verificar_resultados: function(){

            var elementos_correctos = this.elementos.filter(elemento => elemento.resultado == 1)
            var cant_incorrectos = this.elementos.length - elementos_correctos.length

            if ( cant_incorrectos == 0 ) {
                Swal.fire('¡Bien hecho!', 'Tus respuestas son correctas', 'success')
                this.resultado = 1;  //Correcto
                this.play_audio('audio_win')
            } else {
                Swal.fire('Algo no está bien', 'Hay ' + cant_incorrectos + ' respuestas incorrectas o incompletas, revisa nuevamente.', 'warning')
                this.resultado = 0;  //Incorrecto
                this.play_audio('audio_game_over')
            }

            this.guardar_resultado()
        },
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
        play_audio: function(audio_id){
            var audio = document.getElementById(audio_id);
            audio.play();
        },
    }
})
</script>