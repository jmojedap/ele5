<?php $this->load->view('assets/lightbox2') ?>


<div id="preguntas">
    <a href="<?php echo base_url("cuestionarios/pregunta_nueva/{$cuestionario_id}/0") ?>" class="btn btn-light mb-2" title="Agregar pregunta al inicio del cuestionario">
        <i class="fa fa-plus"></i>
        Pregunta al inicio
    </a>
    <a v-bind:href="`<?php echo base_url("cuestionarios/pregunta_nueva/{$cuestionario_id}/") ?>` + lista.length" class="btn btn-light mb-2" title="Agregar pregunta al inicio del cuestionario">
        <i class="fa fa-plus"></i>
        Pregunta al final
    </a>
    <div class="row mb-1" v-for="(pregunta, key) in lista">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" id="pregunta_detail">
                    <p>
                        <span class="badge badge-primary">Pregunta {{ key + 1 }}</span>
                    </p>
                    
                    <p v-html="pregunta.texto_pregunta"></p>
                    <p v-html="pregunta.enunciado_2"></p>
                    <ul style="list-style: none;">
                        <li v-bind:class="{'right_answer': pregunta.clv == 1 }">
                            <span class="badge badge-secondary">A</span>
                            <span v-html="pregunta.opcion_1"></span> 
                        </li>
                        <li v-bind:class="{'right_answer': pregunta.clv == 2 }">
                            <span class="badge badge-secondary">B</span>
                            <span v-html="pregunta.opcion_2"></span> 
                        </li>
                        <li v-bind:class="{'right_answer': pregunta.clv == 3 }">
                            <span class="badge badge-secondary">C</span>
                            <span v-html="pregunta.opcion_3"></span> 
                        </li>
                        <li v-bind:class="{'right_answer': pregunta.clv == 4 }">
                            <span class="badge badge-secondary">D</span>
                            <span v-html="pregunta.opcion_4"></span> 
                        </li>
                    </ul>

                    <a v-bind:href="pregunta.url_imagen_pregunta" data-lightbox="image-1" data-title="Imagen asociada" v-if="pregunta.archivo_imagen" class="btn btn-lg btn-light">
                        <img src="<?php echo URL_IMG ?>flipbook/diapositivas.png" alt="Imagen asociada a la pregunta">
                        Imagen asociada
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div>
                <?php if ( $this->session->userdata('rol_id') <= 2 ) { ?>
                    <a class="btn btn-light" v-bind:href="`<?php echo base_url('preguntas/editar/') ?>` + pregunta.pregunta_id" target="_blank">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                <?php } ?>

                <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
                    <button class="btn btn-light" v-on:click="create_version(key)" v-show="pregunta.version_id == 0" title="Crear una versión alterna de la pregunta">
                        <i class="fa fa-code-branch"></i>
                    </button>
                <?php } ?>

                <a class="btn btn-primary" v-show="pregunta.version_id > 0" title="Ver versión alterna de la pregunta" v-bind:href="`<?php echo base_url('preguntas/version/') ?>` + pregunta.pregunta_id" target="_blank">
                    Versión
                </a>
                <button class="btn btn-warning" v-show="pregunta.version_id > 0" title="Eliminar versión propuesta de esta pregunta" data-toggle="modal" data-target="#delete_version_modal" v-on:click="set_current(key)">
                    <i class="fa fa-trash"></i> Versión
                </button>

                <?php if ( $editable ) { ?>
                    <a class="btn btn-light"
                        v-bind:href="`<?php echo base_url('preguntas/editar/') ?>` + pregunta.pregunta_id"
                        target="_blank"
                        v-show="pregunta.creado_usuario_id == <?php echo $this->session->userdata('usuario_id') ?>"
                        >
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <a v-bind:href="`<?php echo base_url("cuestionarios/pregunta_nueva/{$row->id}/") ?>` + (key + 1)" class="btn btn-light" title="Agregar pregunta después de esta">
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

            <div>
                <p>
                    Tema:
                    <b class="text-success">{{ pregunta.nombre_tema }}</b>
                    
                </p>
            </div>


        </div>
    </div>

    <?php $this->load->view('comunes/bs4/modal_simple_delete_v') ?>

    <!-- Modal Eliminación de Versión -->
    <?php $this->load->view('cuestionarios/preguntas/modal_delete_version_v') ?>

</div>

<?php $this->load->view('cuestionarios/preguntas/vue_v') ?>