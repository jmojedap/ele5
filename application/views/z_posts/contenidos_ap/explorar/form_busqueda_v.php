<?php    
    //Estilo filtros
        foreach ( $arr_filtros as $filtro )
        {
            $style_filtros[$filtro] = 'display: none;';
            if ( strlen($filters[$filtro]) > 0 ) { $style_filtros[$filtro] = ''; }
        }
?>

<form action="<?= base_url("app/buscar/{$controlador}/explorar") ?>">
    <?= form_hidden('f1', $filters['f1']) ?>
    <div class="form-group row">
        <div class="col-sm-4">
            <div class="btn btn-light btn-block" id="alternar_avanzada" data-toggle="tooltip" title="Búsqueda avanzada">
                Búsqueda avanzada
                <i class="fa fa-caret-down float-right"></i>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="input-group">
                <input name="q" autofocus value="<?= $filters['q'] ?>" type="text" class="form-control" placeholder="Buscar contenido...">
                <span class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        Buscar
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group row filtro" style="<?= $style_filtros['f3'] ?>">
        <label for="f3" class="col-sm-4 text-right">Tipo contenido AP</label>
        <div class="col-sm-8">
            <?= form_dropdown('f3', $opciones_tipo_ap, $filters['f3'], 'class="form-control" title="Filtrar por tipo de contenido AP"'); ?>
        </div>
    </div>
    <div class="form-group row filtro" style="<?= $style_filtros['f2'] ?>">
        <label for="f2" class="col-sm-4 text-right">Área</label>
        <div class="col-sm-8">
            <?= form_dropdown('f2', $opciones_area, $filters['f2'], 'class="form-control" title="Filtrar por área"'); ?>
        </div>
    </div>
    <div class="form-group row filtro" style="<?= $style_filtros['n'] ?>">
        <label for="n" class="col-sm-4 text-right">Nivel</label>
        <div class="col-sm-8">
            <?= form_dropdown('n', $opciones_nivel, $filters['n'], 'class="form-control" title="Filtrar por nivel"'); ?>
        </div>
    </div>
</form>