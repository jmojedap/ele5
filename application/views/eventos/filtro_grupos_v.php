<?php

    //Filtro áreas
        $texto_grupo = 'Grupo';
        $clase_todos = 'active';
        $clase_boton = 'btn-secondary';
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
        <a class="dropdown-item <?= $clase_todos ?>" href="<?php echo base_url("{$destino_filtros}?{$get_str}") ?>">
            Todos los grupos
        </a>
        <div class="dropdown-divider"></div>
        <?php foreach ($grupos->result() as $row_grupo) : ?>
            <?php
                $clase_grupo = '';
                if ( $row_grupo->id == $busqueda[''] ) { $clase_grupo = 'active'; }
            ?>
            <a class="dropdown-item <?= $clase_grupo ?>" href="<?php echo base_url("{$destino_filtros}?{$get_str}g={$row_grupo->id}") ?>">
                <?php echo $row_grupo->nivel . '-' . $row_grupo->grupo ?>
            </a>
        <?php endforeach ?>
    </ul>
</div>