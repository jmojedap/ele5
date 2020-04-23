<div id="head_tools">
    <div class="btn-group d-none d-xs-none d-sm-none d-md-block d-lg-block" role="group" aria-label="...">
        <?php if ( ! is_null($ayuda_id) ){ ?>
            <?php $link_ayuda = 'https://www.plataformaenlinea.com/ayuda/?p=' . $ayuda_id; ?>
            <?php echo anchor($link_ayuda,  '<i class="fa fa-question-circle"></i>', 'class="btn btn-success" target="_blank" title="Ir a la ayuda de esta sección"') ?>
        <?php } ?>
        <?php if ( ! is_null($this->session->userdata('arr_selectorp')) ){ ?>
            <a href="<?php echo base_url("preguntas/selectorp") ?>" class="btn btn-warning animated flash" title="Hay preguntas en lista para construir nuevo cuestionario">
                <i class="fa fa-spinner fa-spin"></i>
                Preguntas
            </a>
        <?php } ?>
        <a href="<?php echo base_url('usuarios/contrasena') ?>" class="btn btn-light" style="min-width: 120px;">
            <i class="fa fa-user text-muted mr-1"></i>
            <?php echo $this->session->userdata('nombre_corto') ?>
        </a>
        <a href="<?php echo base_url('app/logout') ?>" class="btn btn-light" title="Cerrar sesión de <?php echo $this->session->userdata('nombre_completo') ?>">
            <i class="fa fa-sign-out-alt"></i>
        </a>
    </div>
</div>