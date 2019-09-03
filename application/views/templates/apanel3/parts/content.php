<div style="float: right; margin: 0px 0px 0 0; margin: 5px 5px 0 0;">
    <div class="btn-group d-none d-xs-none d-sm-none d-md-block d-lg-block" role="group" aria-label="...">
        <?php if ( ! is_null($ayuda_id) ){ ?>
            <?php $link_ayuda = 'https://www.plataformaenlinea.com/ayuda/?p=' . $ayuda_id; ?>
            <?php echo anchor($link_ayuda,  '<i class="fa fa-question-circle"></i>', 'class="btn btn-success" target="_blank" title="Ir a la ayuda de esta sección"') ?>
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

<div class="main_container container-fluid">   
    <h1>
        <span id="head_title"><?php echo $head_title ?></span>
        <?php if ( ! is_null($head_subtitle) ) { ?>
            <span id="head_subtitle" style="font-size: 0.7em; color: #333; padding-left: 0px;" class="hidden-xs">
                <?php echo $head_subtitle ?>
            </span>
        <?php } ?>
    </h1>

    <?php if ( ! is_null($view_description) ) { ?>
        <div id="view_description">
            <?php $this->load->view($view_description) ?>
        </div>
    <?php } ?>

    <?php if ( ! is_null($nav_2) ) { ?>
        <div id="nav_2">
            <?php $this->load->view($nav_2) ?>
        </div>
    <?php } ?>

    <?php if ( ! is_null($nav_3) ) { ?>
        <div id="nav_3">
            <?php $this->load->view($nav_3) ?>
        </div>
    <?php } ?>

    <?php echo $this->load->view($view_a) ?>

    <?php if ( ! is_null($view_b) ) { ?>
        <div id="view_b">
            <?php echo $this->load->view($view_b) ?>
        </div>
    <?php } ?>

</div>
<footer class="main_footer text-right text-muted">&copy; 2019 - En Línea Editores</footer>