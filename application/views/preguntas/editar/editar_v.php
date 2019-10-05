<?php $this->load->view('assets/chosen_jquery') ?>

<div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" id="edicion_pregunta">
                    <?php $this->load->view('preguntas/editar/form_v') ?>
                </div>
                <?php $this->load->view('preguntas/editar/vue_v') ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php $this->load->view('preguntas/editar/archivo_v') ?>
        </div>
    </div>
</div>

