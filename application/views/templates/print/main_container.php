<script>
    $('.dropdown-toggle').dropdown();
</script>

<div class="main_container container-fluid">   
    <div class="row" style="margin-bottom: 5px;">
        <div class="col-md-12">
            <div class="" style="display: inline-block">
                <h1>
                    <?= $titulo_pagina ?>
                    <?php if ( ! is_null($subtitulo_pagina) ) : ?>
                        <span style="font-size: 0.7em; color: #333; padding-left: 0px;" class="hidden-xs"><?= $subtitulo_pagina ?></span>
                    <?php endif ?>
                </h1>
            </div>
        </div>
    </div>
     <?= $this->load->view($vista_a) ?>
</div>
<footer class="main_footer">En Línea Editores &copy; 2018</footer>