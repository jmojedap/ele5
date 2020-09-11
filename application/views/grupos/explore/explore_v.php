<?php $this->load->view('assets/bs4_chosen') ?>

<div id="app_explore">
    <div class="row">
        <div class="col-md-6 d-none d-md-table-cell d-lg-table-cell">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>

        <div class="col">
            <a href="<?php echo base_url("grupos/exportar/?{$str_filters}") ?>" class="btn btn-light" title="Exportar registros encontrados a Excel">
                <i class="fa fa-download"></i>
            </a>
            <button class="btn btn-light d-none"
                title="Eliminar elementos seleccionados"
                data-toggle="modal"
                data-target="#modal_delete"
                v-show="selected.length > 0"
                >
                <i class="fa fa-trash"></i>
            </button>
            
        </div>
        
        <div class="col mb-2">
            <?php $this->load->view('common/vue_pagination_v'); ?>
        </div>
    </div>

    <div id="elements_table">
        <?php $this->load->view($views_folder . 'table_v'); ?>
        <?php $this->load->view($views_folder . 'detail_v'); ?>
    </div>

    <?php $this->load->view('common/modal_delete_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>