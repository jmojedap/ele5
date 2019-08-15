<?php    
    //Clases filtros
        foreach ( $arr_filtros as $filtro )
        {
            $cl_filtros[$filtro] = 'sin_filtrar';
            if ( strlen($busqueda[$filtro]) > 0 ) { $cl_filtros[$filtro] = ''; }
        }
?>

<form accept-charset="utf-8" id="formulario_busqueda" method="POST">
    <div class="form-horizontal">
        <div class="form-group row">
            
            <div class="col-md-9">
                <div class="input-group mb-1">
                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        placeholder="Buscar"
                        autofocus
                        title="Buscar"
                        value="<?php echo $busqueda['q'] ?>"
                        >
                    <div class="input-group-append" title="Buscar">
                        <button type="button" class="btn btn-secondary" id="alternar_avanzada" title="Búsqueda avanzada">
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                
                <button class="btn btn-primary btn-block">
                    <i class="fa fa-search"></i>
                    Buscar
                </button>
            </div>
        </div>

        <div class="form-group row <?php echo $cl_filtros['rol'] ?>">
            <div class="col-md-9">
                <?php echo form_dropdown('rol', $opciones_rol, $busqueda['rol'], 'class="form-control" title="Filtrar por rol de usuario"'); ?>
            </div>
            <label for="a" class="col-md-3 control-label">Rol</label>
        </div>

        <div class="form-group row <?php echo $cl_filtros['i'] ?>">
            <div class="col-md-9">
                <?php echo form_dropdown('i', $opciones_institucion, $busqueda['i'], 'class="form-control" title="Filtrar por institución"'); ?>
            </div>
            <label for="n" class="col-md-3 control-label">Institución</label>
        </div>
    </div>
</form>