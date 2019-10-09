<?php $this->load->view('assets/chosen_jquery') ?>

<a href="<?php echo base_url("preguntas/version/{$row->id}") ?>" class="btn btn-primary mb-2">
    <i class="fa fa-arrow-left"></i>
    Comparar
</a>

<div class="card mb-2">
    <div class="card-body">
        <div id="edicion_pregunta">
            <?php $this->load->view('preguntas/version/form_v') ?>    
        </div>
        <hr>
        <?php $this->load->view('preguntas/version/vue_v') ?>
        <?php $this->load->view('preguntas/version/archivo_v') ?>
    </div>
</div>

