<?php if ( ! IS_NULL($vista_menu) ){ ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<?php $this->load->view('assets/grocery_crud'); ?>

<?= $output; ?>

