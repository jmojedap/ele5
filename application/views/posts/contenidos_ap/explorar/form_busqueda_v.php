<?php    
    //Estilo filtros
        foreach ( $arr_filtros as $filtro )
        {
            $style_filtros[$filtro] = 'display: none;';
            if ( strlen($busqueda[$filtro]) > 0 ) { $style_filtros[$filtro] = ''; }
        }
?>

<form action="<?php echo base_url("app/buscar/{$controlador}/ap_explorar") ?>">
    <?php echo form_hidden('f1', $busqueda['f1']) ?>
    <div class="form-group row">
        <div class="col-sm-4">
            <div class="btn btn-default btn-block btn-outline" id="alternar_avanzada" data-toggle="tooltip" title="Búsqueda avanzada">
                Filtros
                <i class="fa fa-caret-down float-right"></i>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="input-group" style="width: 100%">
                <input name="q" autofocus value="<?= $busqueda['q'] ?>" type="text" class="form-control" placeholder="Buscar contenido...">
                <span class="input-group-btn">
                    <button class="btn btn-info" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group row filtro" style="<?php echo $style_filtros['f3'] ?>">
        <label for="f3" class="col-sm-4 text-right">Tipo contenido AP</label>
        <div class="col-sm-8">
            <?= form_dropdown('f3', $opciones_tipo_ap, $busqueda['f3'], 'class="form-control" title="Filtrar por tipo de contenido AP"'); ?>
        </div>
    </div>
    <div class="form-group row filtro" style="<?php echo $style_filtros['f2'] ?>">
        <label for="f2" class="col-sm-4 text-right">Área</label>
        <div class="col-sm-8">
            <?= form_dropdown('f2', $opciones_area, $busqueda['f2'], 'class="form-control" title="Filtrar por área"'); ?>
        </div>
    </div>
</form>