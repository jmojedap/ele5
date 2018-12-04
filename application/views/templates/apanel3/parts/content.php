<div class="main_container container-fluid">   
    <div class="row" style="margin-bottom: 5px;">
        <div class="col-md-12">
            <div style="float: right; margin: 0px 0px 0 0;">

                <div class="btn-group hidden-xs" role="group" aria-label="...">
                    <?php if ( ! is_null($ayuda_id) ){ ?>
                        <?php $link_ayuda = 'http://www.plataformaenlinea.com/ayuda/?p=' . $ayuda_id; ?>
                        <?php echo anchor($link_ayuda,  '<i class="fa fa-question-circle"></i>', 'class="btn btn-success" target="_blank" title="Ir a la ayuda de esta sección"') ?>
                    <?php } ?>
                    <?php echo anchor("usuarios/contrasena", $this->session->userdata('nombre_corto'), 'class="btn btn-light" style="min-width: 120px;"') ?>
                    <?php echo anchor('app/logout', '<i class="fa fa-sign-out"></i> Salir', 'class="btn btn-light" title="Cerrar sesión de ' . $this->session->userdata('nombre_completo') . '"') ?>
                </div>
            </div>
            
            <div style="display: inline-block">
                <h1>
                    <?php echo $titulo_pagina ?>
                    <?php if ( ! is_null($subtitulo_pagina) ) { ?>
                        <span style="font-size: 0.7em; color: #333; padding-left: 0px;" class="hidden-xs"><?php echo $subtitulo_pagina ?></span>
                    <?php } ?>
                </h1>
            </div>
        </div>
    </div>
    <?php echo $this->load->view($vista_a) ?>
</div>
<footer class="main_footer">En Línea Editores &copy; 2018</footer>