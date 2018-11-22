<?php if ( $this->session->flashdata('resultado') != NULL ): ?>
    <?php
        $resultado = $this->session->flashdata('resultado');
        $clase = 'alert-info';
        $icono = 'fa-info-circle';
        if ( ! is_null($resultado['clase'] ) ) { $clase = $resultado['clase']; }
        if ( ! is_null($resultado['icono'] ) ) { $icono = $resultado['icono']; }
    ?>
    <div class="alert <?= $clase ?> alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        
        <i class="fa <?= $icono ?>"></i>
        <?= $resultado['mensaje'] ?>
    </div>

    <?php if ( isset($resultado['html']) ) { ?>
        <div class="sep2">
            <?= $resultado['html'] ?>
        </div>
    <?php } ?>
<?php endif ?>