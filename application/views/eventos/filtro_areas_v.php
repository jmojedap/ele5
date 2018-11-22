<?php

    //Filtro áreas
        $texto_area = 'Área';
        $clase_areas = 'active';
        $clase_boton = 'btn-default';
        if ( strlen($busqueda['a']) > 0 ) {
            $texto_area = $this->App_model->nombre_item($busqueda['a']);
            $clase_areas = '';
            $clase_boton = 'btn-primary';
        }
        
        $get_str = $this->Pcrn->get_str('a');
?>

<div class="btn-group" style="width: 100%">
    <button type="button" class="btn <?= $clase_boton ?> dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= $texto_area ?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="width: 100%">
        <li class="<?= $clase_areas ?>">
            <?= anchor("{$destino_filtros}?{$get_str}", 'Todas las áreas') ?>
        </li>
        <li role="separator" class="divider"></li>
        <?php foreach ($areas->result() as $row_area) : ?>
            <?php
                $clase_area = '';
                if ( $row_area->id == $busqueda['a'] ) { $clase_area = 'active'; }
            ?>
            <li class="<?= $clase_area ?>">
                <?= anchor("{$destino_filtros}?{$get_str}a={$row_area->id}", $row_area->nombre_area, 'class="" title=""') ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>