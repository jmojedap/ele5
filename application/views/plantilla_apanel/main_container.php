<div class="main_container">
    <div style="float: right;">
        <p>
            <?php if ( ! is_null($ayuda_id) ){ ?>
                <?php $link_ayuda = 'https://www.plataformaenlinea.com/ayuda/?p=' . $ayuda_id; ?>
                <?= anchor($link_ayuda,  '<i class="fa fa-question-circle"></i>', 'class="a2 w1" target="_blank" title="Ir a la ayuda de esta sección"') ?>
            <?php } ?>
            <a class="a2"><?= $this->session->userdata('nombre_completo') ?></a>
            <?= anchor('app/logout', '<i class="fa fa-sign-out"></i> Salir', 'class="a2 w2" title="Cerrar sesión de ' . $this->session->userdata('nombre_completo') . '"') ?>
        </p>
    </div>
    <h1 style="border-bottom: 1px solid #eee; margin-bottom: 5px; padding-bottom: 10px;">
        <?= $titulo_pagina ?>
        <?php if ( ! is_null($subtitulo_pagina) ) : ?>
            <span style="font-size: 0.7em; color: #333; padding-left: 10px;" ><?= $subtitulo_pagina ?></span>
        <?php endif ?>
    </h1>
     <?php $this->load->view($vista_a) ?>
</div>
<footer class="main_footer">En Línea Editores &copy; 2015</footer>