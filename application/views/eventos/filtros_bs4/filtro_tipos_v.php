<?php

    //Filtro áreas
        $texto_tipo = 'Actividad';
        $clase_todos = 'active';
        $clase_boton = 'btn-primary';
        $get_str = $this->Pcrn->get_str('tp');
        if ( $busqueda['tp'] != '' ) {
            $texto_tipo = $this->Item_model->nombre(13, $busqueda['tp']);
            $clase_todos = '';
            $clase_boton = 'btn-primary';
        }         
?>

<div class="btn-group" style="width: 100%">
    <button class="btn dropdown-toggle <?= $clase_boton ?>" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="width: 100%">
        <?= $texto_tipo ?>
    </button>
    <div class="dropdown-menu" style="width: 100%">
        <a class="dropdown-item <?= $clase_todos ?>" href="<?php echo base_url("{$destino_filtros}?{$get_str}") ?>">
            Toda la actividad
        </a>
        <div class="dropdown-divider"></div>
        <?php foreach ($tipos->result() as $row_tipo) : ?>
            <?php
                $clase_tipo = '';
                if ( $row_tipo->id_interno == $busqueda['tp'] ) { $clase_tipo = 'active'; }
            ?>
            <a class="dropdown-item <?= $clase_tipo ?>" href="<?php echo base_url("{$destino_filtros}?{$get_str}tp={$row_tipo->id_interno}") ?>">
                <?php echo $row_tipo->item ?>
            </a>
        <?php endforeach ?>
    </div>
</div>