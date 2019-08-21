<?php
    $nombre_institucion = $this->App_model->nombre_institucion($row->institucion_id, 1);
?>

<p class="d-none d-sm-block">
    <span class="text-danger"><?= $this->Item_model->nombre(6, $row->rol_id); ?></span> | 
    <span class="suave">Username:</span>
    <span class="text-danger"><?= $row->username ?></span> | 
    <span class="suave"><i class="fa fa-bank"></i></span>
    <span class="text-danger"><?= $nombre_institucion ?></span>

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