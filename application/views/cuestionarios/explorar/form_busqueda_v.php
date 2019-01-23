<?php    
    //Clases filtros
        foreach ( $arr_filtros as $filtro )
        {
            $clases_filtros[$filtro] = 'sin_filtrar';
            if ( strlen($busqueda[$filtro]) > 0 ) { $clases_filtros[$filtro] = ''; }
        }
?>

<form accept-charset="utf-8" id="formulario_busqueda" method="POST">
    <div class="form-horizontal">
        <div class="form-group row row">
            <div class="col-md-4">
                <button type="button" class="btn btn-light btn-block" id="alternar_avanzada">
                    Filtros <i class="fa fa-caret-down"></i>
                </button>
            </div>
            <div class="col-md-8">
                <div class="input-group">
                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        placeholder="Buscar cuestionario"
                        autofocus
                        title="Buscar cuestionario"
                        value="<?php echo $busqueda['q'] ?>"
                        >
                    <div class="input-group-append" title="Buscar">
                        <button class="btn btn-primary input-group-addon">
                            <i class="fa fa-search"></i>
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php if ( $this->session->userdata('srol') == 'institucional' ) { ?>
            <div class="form-group row <?php echo $clases_filtros['f1'] ?>">
                <label for="f1" class="col-md-4 control-label text-right">Mostrar</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('f1', $opciones_alcance, $busqueda['f1'], 'class="form-control" id="campo-alcance" title="Solo mis cuestionarios"'); ?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group row <?php echo $clases_filtros['a'] ?>">
            <label for="a" class="col-md-4 control-label text-right">Área</label>
            <div class="col-md-8">
                <?php echo form_dropdown('a', $opciones_area, $busqueda['a'], 'class="form-control" title="Filtrar por área"'); ?>
            </div>
        </div>
        <div class="form-group row <?php echo $clases_filtros['n'] ?>">
            <label for="n" class="col-md-4 control-label text-right">Nivel</label>
            <div class="col-md-8">
                <?php echo form_dropdown('n', $opciones_nivel, $busqueda['n'], 'class="form-control" title="Filtrar por nivel"'); ?>
            </div>
        </div>
        <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
            <div class="form-group row <?php echo $clases_filtros['tp'] ?>">
                <label for="tp" class="col-md-4 control-label text-right">Tipo cuestionario</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control" title="Filtrar por tipo de cuestionario"'); ?>
                </div>
            </div>
            <div class="form-group row <?php echo $clases_filtros['i'] ?>">
                <label for="i" class="col-md-4 control-label text-right">Institución</label>
                <div class="col-md-8">
                    <?php echo form_dropdown('i', $opciones_institucion, $busqueda['i'], 'class="form-control chosen-select" title="Filtrar por institución"'); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</form>