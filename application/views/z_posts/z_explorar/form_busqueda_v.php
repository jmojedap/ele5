<?php    
    //Estilo filtros
        foreach ( $arr_filtros as $filtro )
        {
            $style_filtros[$filtro] = 'display: none;';
            if ( strlen($busqueda[$filtro]) > 0 ) { $style_filtros[$filtro] = ''; }
        }
?>

<form action="<?php echo base_url("app/buscar/{$controlador}/explorar") ?>">
    <div class="form-group row">
        <div class="col-sm-4">
            <div class="btn btn-default btn-block btn-outline" id="alternar_avanzada" data-toggle="tooltip" title="Búsqueda avanzada">
                Filtros
                <i class="fa fa-caret-down float-right"></i>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="input-group" style="width: 100%">
                <input name="q" autofocus value="<?= $busqueda['q'] ?>" type="text" class="form-control" placeholder="Buscar usuario...">
                <span class="input-group-btn">
                    <button class="btn btn-info" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group row filtro" style="<?php echo $style_filtros['tp'] ?>">
        <label for="tp" class="col-sm-4 col-form-label text-right">Tipo</label>
        <div class="col-sm-8">
            <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control" title="Filtrar por rol de usuario"'); ?>
        </div>
    </div>
</form>