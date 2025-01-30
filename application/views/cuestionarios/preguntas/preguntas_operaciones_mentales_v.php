<?php $this->load->view('assets/lightbox2') ?>


<div id="preguntas" class="container">
    <?php if ( $row->tipo_id > 3 or $this->session->userdata('role') < 3   ) : ?>
        <a href="<?= base_url("cuestionarios/pregunta_nueva/{$cuestionario_id}/0") ?>" class="btn btn-light mb-2" title="Agregar pregunta al inicio del cuestionario">
            <i class="fa fa-plus"></i>
            Pregunta al inicio
        </a>
        <a v-bind:href="`<?= base_url("cuestionarios/pregunta_nueva/{$cuestionario_id}/") ?>` + lista.length" class="btn btn-light mb-2" title="Agregar pregunta al inicio del cuestionario">
            <i class="fa fa-plus"></i>
            Pregunta al final
        </a>
    <?php endif; ?>
    <div class="row mb-1" v-for="(pregunta, key) in lista">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" id="pregunta_detail">
                    <p>
                        <span class="badge badge-primary">Pregunta {{ key + 1 }}</span>
                    </p>
                    <p>
                        {{ pregunta.habilidad }} &middot; {{ pregunta.proceso_pensamiento }}
                    </p>
                    
                    <p v-html="pregunta.texto_pregunta"></p>
                    <p v-html="pregunta.enunciado_2"></p>
                    <ul style="list-style: none;">
                        <li v-bind:class="{'right_answer': pregunta.clv == 1 }">
                            <span class="badge badge-secondary">Nivel básico</span>
                            <br>
                            <span v-html="pregunta.opcion_1"></span> 
                        </li>
                        <li v-bind:class="{'right_answer': pregunta.clv == 2 }">
                            <span class="badge badge-secondary">Nivel medio</span>
                            <br>
                            <span v-html="pregunta.opcion_2"></span> 
                        </li>
                        <li v-bind:class="{'right_answer': pregunta.clv == 3 }">
                            <span class="badge badge-secondary">Nivel avanzado</span>
                            <br>
                            <span v-html="pregunta.opcion_3"></span> 
                        </li>
                    </ul>

                    <a v-bind:href="pregunta.url_imagen_pregunta" data-lightbox="image-1" data-title="Imagen asociada" v-if="pregunta.archivo_imagen" class="btn btn-lg btn-light">
                        <img src="<?= URL_IMG ?>flipbook/diapositivas.png" alt="Imagen asociada a la pregunta">
                        Imagen asociada
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div>
                <?php if ( $this->session->userdata('rol_id') <= 2 ) { ?>
                    <a class="btn btn-light" v-bind:href="`<?= base_url('preguntas/editar/') ?>` + pregunta.pregunta_id" target="_blank">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                <?php } ?>

                <?php if ( $editable && in_array($row->tipo_id,[5])  ) { ?>
                    <a class="btn btn-light"
                        v-bind:href="`<?= base_url('preguntas/editar/') ?>` + pregunta.pregunta_id"
                        target="_blank"
                        v-show="pregunta.creado_usuario_id == <?= $this->session->userdata('usuario_id') ?>"
                        >
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <a v-bind:href="`<?= base_url("cuestionarios/pregunta_nueva/{$row->id}/") ?>` + (key + 1)" class="btn btn-light" title="Agregar pregunta después de esta">
                        <i class="fa fa-plus"></i>
                    </a>
                    <button class="btn btn-light" v-on:click="move_question(key, key - 1)">
                        <i class="fa fa-caret-up"></i>
                    </button>
                    <button class="btn btn-light" v-on:click="move_question(key, key + 1)">
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <button class="btn btn-light" title="Quitar pregunta del cuestionario" data-toggle="modal" data-target="#delete_modal" v-on:click="set_current(key)">
                        <i class="fa fa-times"></i>
                    </button>
                <?php } ?>
            </div>


        </div>
    </div>

    <?php $this->load->view('comunes/bs4/modal_simple_delete_v') ?>

    <!-- Modal Eliminación de Versión -->
    <?php $this->load->view('cuestionarios/preguntas/modal_delete_version_v') ?>

</div>

<?php $this->load->view('cuestionarios/preguntas/vue_v') ?>