<p class="d-none d-sm-block">
    <span class="text-danger"><?= $this->Item_model->nombre(6, $row->rol_id); ?></span> 
         
    <span class="suave">| Username:</span>
    <span class="text-danger"><?= $row->username ?></span>

    <span class="text-muted">|</span>

    <?php if ( $this->session->userdata('rol_id') <= 1 ) { ?>
        <span class="text-muted">
            Acceder como 
        </span>
        <a href="<?php echo base_url("develop/ml/{$usuario_id}") ?>" class="">
            <i class="fa fa-user"></i>
            <?php echo $row->username ?>
        </a>
    <?php } ?>
</p>
            
