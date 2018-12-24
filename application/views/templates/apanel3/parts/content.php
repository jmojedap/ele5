<div style="float: right; margin: 0px 0px 0 0; margin: 5px 5px 0 0;">
    <div class="btn-group d-none d-xs-none d-sm-none d-md-block d-lg-block" role="group" aria-label="...">
        <?php if ( ! is_null($ayuda_id) ){ ?>
            <?php $link_ayuda = 'http://www.plataformaenlinea.com/ayuda/?p=' . $ayuda_id; ?>
            <?php echo anchor($link_ayuda,  '<i class="fa fa-question-circle"></i>', 'class="btn btn-success" target="_blank" title="Ir a la ayuda de esta sección"') ?>
        <?php } ?>
        <a href="<?php echo base_url('usuarios/contrasena') ?>" class="btn btn-light" style="min-width: 120px;">
            <?php echo $this->session->userdata('nombre_corto') ?>
        </a>
        <a href="<?php echo base_url('app/logout') ?>" class="btn btn-light" title="Cerrar sesión de <?php echo $this->session->userdata('nombre_completo') ?>">
            <i class="fa fa-sign-out-alt"></i>
        </a>
    </div>
</div>

<div class="main_container container-fluid">   
    <h1>
        <span id="titulo_pagina"><?php echo $titulo_pagina ?></span>
        <?php if ( ! is_null($subtitulo_pagina) ) { ?>
            <span id="subtitulo_pagina" style="font-size: 0.7em; color: #333; padding-left: 0px;" class="hidden-xs">
                <?php echo $subtitulo_pagina ?>
            </span>
        <?php } ?>
    </h1>
    <?php echo $this->load->view($vista_a) ?>
</div>
<footer class="main_footer">En Línea Editores &copy; 2019</footer>