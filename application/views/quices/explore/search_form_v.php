<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    type="text" name="q"
                    class="form-control" placeholder="Buscar"
                    autofocus
                    title="Buscar"
                    v-model="filters.q" v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn btn-secondary btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="displayFilters"></i>
                        <i class="fa fa-chevron-down" v-show="!displayFilters"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    <div id="adv_filters" v-show="displayFilters">
        <div class="form-group row">
            <div class="col-md-9">
                <select name="a" class="form-control">
                    <option v-for="(option_area, area_key) in options_area" v-bind:value="area_key">{{ option_area }}</option>
                </select>
            </div>
            <label for="a" class="col-md-3 col-form-label">Área</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="n" class="form-control">
                    <option v-for="(option_nivel, nivel_key) in options_nivel" v-bind:value="nivel_key">{{ option_nivel }}</option>
                </select>
            </div>
            <label for="n" class="col-md-3 col-form-label">Nivel</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="tp" class="form-control">
                    <option v-for="(option_tipo, tipo_key) in options_tipo" v-bind:value="tipo_key">{{ option_tipo }}</option>
                </select>
            </div>
            <label for="n" class="col-md-3 col-form-label">Tipo</label>
        </div>
    </div>
</form>