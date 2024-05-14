<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/toastr'); ?>
<?php $this->load->view('assets/bs_checkboxes'); ?>

<?php $this->load->view("{$carpeta_vistas}script_js");?>

<?php if ( in_array($this->session->userdata('role'), array(0,1,2)) ) { ?>
    <?php $this->load->view("{$carpeta_vistas}menu_v"); ?>
<?php } else { ?>
    <?php $this->load->view("{$carpeta_vistas}menu_externos_v"); ?>
<?php } ?>

<div class="row my-2">
    <div class="col-md-6">
        <?php $this->load->view("{$carpeta_vistas}form_busqueda_v"); ?>
    </div>
    <div class="col-md-3">
        <?php if ( $this->session->userdata('role') <= 2 ) { ?>
            <div class="btn-group" id="withBtnGroup" role="group">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal_eliminar">
                    <span title="Eliminar registros seleccionados" data-toggle="tooltip">
                        <i class="fa fa-trash"></i>
                    </span>
                </button>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-3">
        <?php $this->load->view('comunes/bs4/paginacion_ajax_v'); ?>
    </div>
</div>

<div id="tabla_resultados">
    <?php $this->load->view("{$carpeta_vistas}tabla_v"); ?>
</div>

<?php $this->load->view('comunes/bs4/modal_eliminar_v'); ?>
