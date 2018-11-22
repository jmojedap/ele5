<?php $this->load->view('assets/grocery_crud'); ?>

<script>
    $(document).ready(function()
    {
        $('ul.connected-list').css( "height", "+=600" );
        $('.main_nav_col').css( "height", "+=600" );
    });
</script>

<?php $this->load->view('programas/editar_submenu_v'); ?>

<div class="sep1">
    <div class="btn-group">
        <button type="button" class="btn btn-default w4"><?php echo $this->Item_model->nombre(3, $nivel, 'item_largo'); ?></button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a>Nivel de temas disponibles</a>
            </li>
            <li role="separator" class="divider"></li>
            <?php foreach ($niveles->result() as $row_nivel) : ?>
                <?php $clase_nivel = $this->Pcrn->clase_activa($row_nivel->id_interno, $nivel, 'active') ?>
                <li class="<?= $clase_nivel ?>">
                    <?= anchor("programas/editar_temas/edit/{$row->id}/?n={$row_nivel->id_interno}&tp={$tipo_id}", $row_nivel->nivel) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="btn-group">
        <button type="button" class="btn btn-default w4"><?= $this->Item_model->nombre(17, $tipo_id); ?></button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a>Seleccione el tipo de tema</a>
            </li>
            <li role="separator" class="divider"></li>
            <?php foreach ($tipos_tema->result() as $row_tipo) : ?>
                <?php
                    $clase_tipo = $this->Pcrn->clase_activa($row_tipo->id_interno, $tipo_id, 'active');
                ?>
                <li class="<?= $clase_tipo ?>">
                    <?= anchor("programas/editar_temas/edit/{$row->id}?tp={$row_tipo->id_interno}&n={$nivel}", $row_tipo->tipo_tema) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</div>

<div class="alert alert-info" role="alert">
    <i class="fa fa-info-circle"></i> No olvide hacer clic en el botón <span class="label label-success">Actualizar cambios</span> después de seleccionar y ordenar los temas.
</div>

<?php echo $output; ?>