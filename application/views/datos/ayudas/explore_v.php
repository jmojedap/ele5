<div id="app_explore">
    <div class="row">
        <div class="col-md-6 d-none d-md-table-cell d-lg-table-cell">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>
        
        <div class="col mb-2">
            <?php $this->load->view('common/vue_pagination_v'); ?>
            <?php if ( $this->session->userdata('role') <= 2 ) { ?>
                <a href="<?= base_url("posts/add/20") ?>" class="btn btn-info w120p">
                    <i class="fa fa-plus"></i>
                    Nuevo
                </a>
            <?php } ?>
        </div>
    </div>

    <div id="elements_table">
        <?php $this->load->view($views_folder . 'table_v'); ?>
        <?php $this->load->view($views_folder . 'detail_v'); ?>
    </div>

    <?php //$this->load->view('common/modal_delete_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>