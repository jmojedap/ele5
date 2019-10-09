<div id="preguntas">
    <div class="row mb-1" v-for="(pregunta, key) in lista">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="">Pregunta {{ key + 1 }}</h3>
                    <p v-html="pregunta.texto_pregunta"></p>
                    <ul style="list-style: none;">
                        <li><span class="badge badge-success">A</span> {{ pregunta.opcion_1 }}</li>
                        <li><span class="badge badge-success">B</span> {{ pregunta.opcion_2 }}</li>
                        <li><span class="badge badge-success">C</span> {{ pregunta.opcion_3 }}</li>
                        <li><span class="badge badge-success">D</span> {{ pregunta.opcion_4 }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php if ( $this->session->userdata('rol_id') <= 2 ) { ?>
                <a class="btn btn-secondary w2" v-bind:href="`<?php echo base_url('preguntas/editar/') ?>` + pregunta.pregunta_id" target="_blank">
                    <i class="fa fa-pencil-alt"></i>
                </a>
            <?php } ?>
            <button class="btn btn-secondary w2" v-on:click="create_version(key)" v-show="pregunta.version_id == 0">
                <i class="fa fa-code-branch"></i>
            </button>
        </div>
    </div>
</div>

<?php $this->load->view('cuestionarios/preguntas/vue_v') ?>