<?php foreach ($noticias->result() as $row_noticia) : ?>
<div class="noticia" id="ev_<?= $row_noticia->id ?>">
    <?php if ( in_array($row_noticia->tipo_id, array(1,2,3,11,12,50)) ){ ?>
        <?php
            $vista_noticia = "eventos/noticias/tipo_{$row_noticia->tipo_id}_v";
            $data_noticia['row_noticia'] = $row_noticia;
            $html_noticia = $this->load->view($vista_noticia, $data_noticia, TRUE);
        ?>
        <?php echo $html_noticia; ?>
    <?php } else { ?>
    <span class="text-muted">
        En construcción
    </span>
    <?php } ?>
</div>
<?php endforeach ?>