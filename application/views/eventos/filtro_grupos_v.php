<?php

    //Filtro áreas
        $texto_grupo = 'Grupo';
        $clase_todos = 'active';
        $clase_boton = 'btn-default';
        $get_str = $this->Pcrn->get_str('g');
        if ( $busqueda['g'] != '' ) {
            $texto_grupo = 'Grupo ' . $this->App_model->nombre_grupo($busqueda['g']);
            $clase_todos = '';
            $clase_boton = 'btn-primary';
        }         
?>

<div class="btn-group" style="width: 100%">
    <button type="button" class="btn <?= $clase_boton ?> dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= $texto_grupo ?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="width: 100%">
        <li class="<?= $clase_todos ?>">
            <?= anchor("{$destino_filtros}?{$get_str}", 'Todos los grupos') ?>
        </li>
        <li role="separator" class="divider"></li>
        <?php foreach ($grupos->result() as $row_grupo) : ?>
            <?php
                $clase_grupo = '';
                if ( $row_grupo->id == $busqueda[''] ) { $clase_grupo = 'active'; }
            ?>
            <li class="<?= $clase_grupo ?>">
                <?= anchor("{$destino_filtros}?{$get_str}g={$row_grupo->id}", $row_grupo->nivel . '-' . $row_grupo->grupo, 'class="" title=""') ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>