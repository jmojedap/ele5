<?php
    //Filtro áreas
        $texto_area = 'Área';
        $clase_areas = 'active';
        $clase_boton = 'btn-secondary';
        if ( strlen($busqueda['a']) > 0 )
        {
            $texto_area = $this->App_model->nombre_item($busqueda['a']);
            $clase_areas = '';
            $clase_boton = 'btn-primary';
        }
        
        $get_str = $this->Pcrn->get_str('a');
?>

<div class="btn-group" style="width: 100%">
    <button type="button" class="btn <?= $clase_boton ?> dropdown-toggle btn-block" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?= $texto_area ?>
    </button>
    <ul class="dropdown-menu" style="width: 100%">
        <a class="dropdown-item <?= $clase_areas ?>" href="<?php echo base_url("{$destino_filtros}?{$get_str}") ?>">
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
    </ul>
</div>