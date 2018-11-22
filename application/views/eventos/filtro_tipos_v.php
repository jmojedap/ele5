<?php

    //Filtro áreas
        $texto_tipo = 'Actividad';
        $clase_todos = 'active';
        $clase_boton = 'btn-default';
        $get_str = $this->Pcrn->get_str('tp');
        if ( $busqueda['tp'] != '' ) {
            $texto_tipo = $this->Item_model->nombre(13, $busqueda['tp']);
            $clase_todos = '';
            $clase_boton = 'btn-primary';
        }         
?>

<div class="btn-group" style="width: 100%">
    <button type="button" class="btn <?= $clase_boton ?> dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= $texto_tipo ?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="width: 100%">
        <li class="<?= $clase_todos ?>">
            <?= anchor("{$destino_filtros}?{$get_str}", 'Toda la actividad') ?>
        </li>
        <li role="separator" class="divider"></li>
        <?php foreach ($tipos->result() as $row_tipo) : ?>
            <?php
                $clase_tipo = '';
                if ( $row_tipo->id_interno == $busqueda['tp'] ) { $clase_tipo = 'active'; }
            ?>
            <li class="<?= $clase_tipo ?>">
                <?= anchor("{$destino_filtros}?{$get_str}tp={$row_tipo->id_interno}", $row_tipo->item, 'class="" title=""') ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>