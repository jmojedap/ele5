<form accept-charset="utf-8" id="form_busqueda" @submit.prevent="buscar">
    <div class="form-group row">
        <label for="q" class="col-md-4 control-label">
            <button class="btn btn-default btn-block" type="button" v-on:click="alternar_filtros">
                Filtros
                <i class="fa fa-caret-down"></i>
            </button>
        </label>
        <div class="col-md-8">
            <div class="input-group">
                <input
                    name="q"
                    class="form-control"
                    placeholder="Buscar usuario..."
                    title="Buscar usuario"
                    autofocus
                    >
                <div class="input-group-append">
                    <button class="btn btn-primary input-group-addon">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row" v-show="mostrar_filtros">
        <label for="rol" class="col-md-4 control-label text-right">
            Rol
        </label>
        <div class="col-md-8">
            <?= form_dropdown('rol', $opciones_rol, '', 'class="form-control" title="Filtrar por rol de usuario"'); ?>
        </div>
    </div>    
</form>