<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    place="text"
                    name="q"
                    class="form-control"
                    placeholder="Buscar"
                    autofocus
                    title="Buscar"
                    v-model="filters.q"
                    v-on:change="get_list"
                    >
                <div class="input-group-append" title="Search">
                    <button type="button" class="btn btn-secondary btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="showing_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!showing_filters"></i>
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
    <div id="adv_filters" style="<?php echo $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('tp', $options_type, $filters['tp'], 'class="form-control" title="Filtrar por tipo" v-model="filters.tp"'); ?>
            </div>
            <label for="type" class="col-md-3 col-form-label">Tipo evento</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <input name="fi" id="field-fi" type="date" class="form-control" v-model="filters.fi">
            </div>
            <label for="type" class="col-md-3 col-form-label">Creado desde</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <input name="ff" id="field-ff" type="date" class="form-control" v-model="filters.ff">
            </div>
            <label for="type" class="col-md-3 col-form-label">Creado hasta</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <input name="i" id="field-i" type="number" class="form-control" v-model="filters.i">
            </div>
            <label for="type" class="col-md-3 col-form-label">ID Institución</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <input name="g" id="field-g" type="number" class="form-control" v-model="filters.g">
            </div>
            <label for="type" class="col-md-3 col-form-label">ID Grupo</label>
        </div>
    </div>
</form>