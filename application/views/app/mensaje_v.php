<div class="alert alert-info" role="alert">
    <i class="fa fa-info-circle"></i>
    <?= $mensaje ?>
</div>

<?php if ( ! is_null($link_volver) ) { ?>
    <?= anchor($link_volver, 'Volver', 'class="btn btn-default" title="Volver"') ?>
<?php } ?>