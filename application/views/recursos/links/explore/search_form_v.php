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
                <div class="input-group-append" title="Buscar">
                    <button a="button" class="btn btn-secondary btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="displayFilters"></i>
                        <i class="fa fa-chevron-down" v-show="!displayFilters"></i>
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
    <div id="adv_filters" v-show="displayFilters">
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('a', $options_area, $filters['a'], 'class="form-control" title="Filtrar por área" v-model="filters.a"'); ?>
            </div>
            <label for="a" class="col-md-3 col-form-label">Área</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('n', $options_nivel, $filters['n'], 'class="form-control" title="Filtrar por nivel" v-model="filters.n"'); ?>
            </div>
            <label for="n" class="col-md-3 col-form-label">Nivel</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('cpnt', $options_componente, $filters['cpnt'], 'class="form-control" title="Filtrar por componente" v-model="filters.cpnt"'); ?>
            </div>
            <label for="cpnt" class="col-md-3 col-form-label">Componente</label>
        </div>
    </div>
</form>