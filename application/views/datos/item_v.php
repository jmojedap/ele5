<?= $this->load->view('datos/parametros_menu_v') ?>

<div class="bs-caja">
    <ul class="nav nav-pills">
        <?php foreach ($arr_categorias as $key => $nombre_categoria) : ?>
            <?php
                $clase = '';
                if ( $categoria_id == $key ) { $clase = 'active'; }
            ?>
            <li role="presentation" class="<?= $clase ?>">
                <?= anchor("datos/item/{$key}", $nombre_categoria) ?>
            </li>

        <?php endforeach ?>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="bs-caja-no-padding">
            <?php echo $output; ?>
        </div>
    </div>
</div>




