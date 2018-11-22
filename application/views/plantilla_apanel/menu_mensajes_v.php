<i class="fa fa-2x fa-comments"></i>

<span>
    <?php if ( $this->session->userdata('no_leidos') > 0 ){ ?>
        <b class="etiqueta primario">
            <?= $this->session->userdata('no_leidos') ?>
        </b>
    <?php } ?>
    mensajes
</span>
