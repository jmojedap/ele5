<?php

    //Filtro áreas
        $texto_area = 'Área';
        $clase_areas = 'active';
        $clase_boton = 'btn-primary';
        if ( strlen($busqueda['a']) > 0 ) {
            $texto_area = $this->App_model->nombre_item($busqueda['a']);
            $clase_areas = '';
            $clase_boton = 'btn-primary';
        }
        
        $get_str = $this->Pcrn->get_str('a');
?>

<div class="dropdown">
    <button class="btn dropdown-toggle <?= $clase_boton ?>" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="width: 100%">
        <?php echo $texto_area ?>
    </button>
    <div class="dropdown-menu" style="width: 100%">
        <a href="<?php echo base_url("{$destino_filtros}?{$get_str}") ?>" class="dropdown-item <?= $clase_areas ?>">
            Todas las áreas
        </a>
        <div class="dropdown-divider"></div>
        <?php foreach ($areas->result() as $row_area) : ?>
            <?php
                $clase_area = '';
                if ( $row_area->id == $busqueda['a'] ) { $clase_area = 'active'; }
            ?>
            <a class="dropdown-item <?= $clase_area ?>" href="<?php echo base_url("{$destino_filtros}?{$get_str}a={$row_area->id}") ?>">
                <?php echo $row_area->nombre_area ?>
            </a>
        <?php endforeach ?>
    </div>
</div>