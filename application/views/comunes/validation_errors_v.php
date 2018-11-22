<?php if ( validation_errors() ):?>
    <?= validation_errors('<div class="alert alert-danger" role="alert">', '</div>') ?>
<?php endif ?>