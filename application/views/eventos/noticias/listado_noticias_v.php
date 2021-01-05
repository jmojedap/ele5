<?php
    $arr_tipos_visibles = array(1,2,3,4,11,12,13,21,50,101,107);
?>

<?php foreach ($noticias->result() as $row_noticia) : ?>

    <?php
        $vista_noticia = "eventos/noticias/tipo_{$row_noticia->tipo_id}_v";
        $data_noticia['row_noticia'] = $row_noticia;
    ?>

    <div class="noticia" id="ev_<?= $row_noticia->id ?>">
        <?php if ( in_array($row_noticia->tipo_id, $arr_tipos_visibles) ){ ?>
            <?php $this->load->view($vista_noticia, $data_noticia); ?>
        <?php } else { ?>
            <span class="text-muted">
                No disponible
            </span>
        <?php } ?>
    </div>
<?php endforeach ?>