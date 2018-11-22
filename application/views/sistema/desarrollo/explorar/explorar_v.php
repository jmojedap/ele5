<?php $this->load->view('assets/bs_checkboxes'); ?>

<div id="app_explorar">
    <div class="row">
        <div class="col col-md-6">
            <?php $this->load->view("{$carpeta_vistas}form_busqueda_v"); ?>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal_eliminar" v-if="seleccionados">
                <span title="Eliminar registros seleccionados" data-toggle="tooltip">
                    <i class="fa fa-trash"></i>
                </span>
            </button>
            <a href="<?php echo base_url('usuarios/exportar') ?>" class="btn btn-light" title="Descargar en Excel">
                <i class="fa fa-download"></i>
            </a>
        </div>
        <div class="col col-md-1">
            <span class="badge badge-light float-rigth">{{ cant_resultados }} resultados</span>
            <span class="badge badge-light float-rigth">{{ max_pagina }} páginas</span>
        </div>
        <div class="col col-md-2">
            <?php $this->load->view('comunes/paginacion_vue_v'); ?>
        </div>
    </div>
    
    <?php $this->load->view("{$carpeta_vistas}tabla_v"); ?>
    <pre class="d-none">
        {{ $data | json }}
    </pre>
    
    <?php $this->load->view('comunes/modal_eliminar_v'); ?>
</div>

<script src="<?php echo URL_RECURSOS ?>js/pcrn.js"></script>

<?php $this->load->view("{$carpeta_vistas}vue_v"); ?>