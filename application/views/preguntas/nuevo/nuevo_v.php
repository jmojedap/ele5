<?php $this->load->view('assets/chosen_jquery') ?>
<?php $this->load->view('assets/summernote_editores') ?>

<a href="<?php echo base_url("cuestionarios/preguntas/{$row->id}") ?>" class="btn btn-secondary mb-2">
    <i class="fa fa-arrow-left"></i>
    Volver a preguntas
</a>

<div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" id="pregunta_nueva">
                    <?php $this->load->view($view_form) ?>
                </div>
                <?php $this->load->view('preguntas/nuevo/vue_v') ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php $this->load->view('preguntas/nuevo/archivo_v') ?>
        </div>
    </div>
</div>

