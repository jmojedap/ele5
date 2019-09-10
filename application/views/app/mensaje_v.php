<div class="alert alert-info" role="alert">
    <i class="fa fa-info-circle"></i>
    <?= $mensaje ?>
</div>

<?php if ( ! is_null($link_volver) ) { ?>
    <a href="<?php echo base_url($link_volver) ?>" class="btn btn-primary">
        <i class="fa fa-arrow-left"></i>
        Volver
    </a>
<?php } ?>