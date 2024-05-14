<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/toastr'); ?>
<?php $this->load->view('assets/bs_checkboxes'); ?>

<script src="<?php echo base_url('js/pcrn.js') ?>"></script>

<?php $this->load->view("{$carpeta_vistas}script_js");?>

<?php $this->load->view("{$carpeta_vistas}menu_v"); ?>


<div style="margin: 10px 0px 0px 0px;">
    <div class="row">
        <div class="col col-md-6">
            <?php $this->load->view("{$carpeta_vistas}form_busqueda_v"); ?>
        </div>
        <div class="col col-md-3">
            <div class="btn-group" id="withBtnGroup" role="group">
                <a role="button" class="btn btn-outline btn-default" href="<?= base_url("{$controlador}/exportar/?{$busqueda_str}") ?>" data-toggle="tooltip" title="Exportar resultados a Excel">
                    <i class="fa fa-file-excel-o"></i>
                </a>
                <button type="button" class="btn btn-outline btn-default" data-toggle="modal" data-target="#modal_eliminar">
                    <span title="Eliminar registros seleccionados" data-toggle="tooltip">
                        <i class="fa fa-trash"></i>
                    </span>
                </button>
            </div>
        </div>
        <div class="col col-md-3">
            <?php $this->load->view('comunes/paginacion_ajax_v'); ?>
        </div>
    </div>
</div>

<div id="tabla_resultados">
    <?php $this->load->view("{$carpeta_vistas}tabla_v"); ?>
</div>

<?php $this->load->view('comunes/modal_eliminar'); ?>
