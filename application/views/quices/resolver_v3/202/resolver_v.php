<link rel="stylesheet" href="<?= URL_RESOURCES  ?>css/quices/quices_v3.css">

<div id="resolverQuiz">
    <div class="center_box_750">
        <div class="progress my-3 w-100" style="height: 3px;">
            <div class="progress-bar bg-primary" id="time-progress-bar" role="progressbar" style="width: 0%"></div>
        </div>
    </div>
    <div class="center_box_750 quiz-container">
        <!-- INICIO -->
        <div v-show="step ==  `inicio`" class="text-center w-100 mb-2">
            <h3>Práctica Lectora</h3>
            <p>
                Lee el texto y selecciona la palabra relacionada con la lectura
            </p>
            <button class="btn btn-warning btn-lg" v-on:click="setCurrent(0)">
                INICIAR PRÁCTICA
            </button>
        </div>        

        <!-- RESPUESTA  -->
        <div v-show="step ==  `respuesta`" class="w-100">
        <!-- <div class="w-100"> -->
            <nav class="my-2 d-none">
                <ul class="pagination">
                    <li class="page-item" v-for="(quiz, k) in quices" v-on:click="setCurrent(k)"
                        v-bind:class="{'active': k == currentKey }">
                        <button class="page-link" type="button">
                            {{ k + 1 }}
                        </button>
                    </li>
                </ul>
            </nav>
            <p class="text-center lead text-muted">
                {{ currentKey + 1 }}/{{ quices.length }}
            </p>
            <div class="">
                <div class="d-none">
                    respuesta correcta: {{ currentQuiz.clave }}
                    &middot;
                    opción seleccionada: {{ currentQuiz.respuesta }}
                    &middot;
                    resultado {{ currentQuiz.resultado }}
                    &middot;
                    resultado total: {{ resultadoTotal }}
                    &middot;
                    porcentaje total: {{ porcentajeTotal }}
                    &middot;
                    respuestas completas: {{ respuestasCompletas }}
                    &middot;
                    respuestas completas: {{ currentQuiz }}
                </div>
                
                <div v-show="status == 'leyendo'">
                    <p class="lead text-center text-muted">
                        Lee el texto y selecciona la palabra relacionada con la lectura
                    </p>
                    <p class="lectura">
                        {{ currentQuiz.texto_enunciado }}
                    </p>
                </div>
                
                <div class="text-center mb-2 center_box_320" v-show="status == 'respondiendo'">
                    <p class="lead text-center text-muted mb-2">Selecciona la palabra relacionada con la lectura</p>
                    <div v-for="opcion in opciones" class="mb-3">
                        <button class="btn me-1 btn-lg w-100" type="button"
                            v-on:click="seleccionarOpcion(opcion)"
                            v-bind:class="optionClass(opcion)"
                            v-bind:disabled="currentQuiz.comprobado == 1"
                            >
                            <span v-show="currentQuiz.comprobado == 1">
                                <i class="fas fa-check" v-show="opcion == currentQuiz.respuesta && currentQuiz.resultado == 1"></i>
                                <i class="fas fa-times" v-show="opcion == currentQuiz.respuesta && currentQuiz.resultado == 0"></i>
                            </span>
                            {{ opcion }}
                        </button>
                        <br>
                    </div>
                </div>

                <div class="center_box_320">
                    <form accept-charset="utf-8" method="POST" id="quizForm" @submit.prevent="handleSubmit">
                        <fieldset v-bind:disabled="loading">
                            <input type="hidden" name="usuario_id" value="<?= $this->session->userdata('user_id') ?>">
                            <input type="hidden" name="quiz_id" value="<?= $row->id ?>">
                            <input type="hidden" name="resultado" v-model="porcentajeTotal">
                            
                            <div class="text-center" v-show="status == `respondiendo`">
                                
        
                                <button class="btn btn-success" type="button"
                                    v-on:click="comprobarRespuesta"
                                    v-show="currentQuiz.comprobado == 0"
                                    v-bind:disabled="currentQuiz.respuesta == ''">
                                    COMPROBAR
                                </button>
                                <button class="btn btn-primary" type="button"
                                    v-on:click="setCurrent(currentKey+1)"
                                    v-show="currentKey + 1 < quices.length && currentQuiz.comprobado == 1"
                                    v-bind:disabled="currentQuiz.respondido == 0">
                                    SIGUIENTE <i class="fas fa-arrow-right"></i>
                                </button>

                                <button class="btn btn-primary" type="submit"
                                    v-show="currentKey + 1 == quices.length && currentQuiz.comprobado == 1"
                                    v-bind:disabled="!respuestasCompletas">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
        
                        <fieldset>
                    </form>
                </div>
    
            </div>
        </div>

        <!-- FINALIZADO -->
        <div v-show="step ==  `finalizado`" class="text-center w-100">
            <h3>Resultado</h3>

            <div class="text-center">
                <table class="w120p mb-3" style="margin: 0 auto;">
                    <tbody>
                        <tr class="border-bottom"><td class="display-3 text-primary">{{ resultadoTotal }}</td></tr>
                        <tr><td class="display-3">{{ quices.length }}</td></tr>
                    </tbody>
                </table>
            </div>

            <div>
                <button class="btn btn-light btn-lg me-2 w150p" v-on:click="reiniciar">
                    Reintentar
                </button>
                <button class="btn btn-primary btn-lg w150p d-none">
                    Finalizar
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('quices/resolver_v3/202/vue_v') ?>