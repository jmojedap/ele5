<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-8">
            <div class="input-group mb-2">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Buscar"
                    autofocus
                    title="Buscar"
                    v-model="filters.q"
                    v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button status="button" class="btn btn-secondary btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="showing_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!showing_filters"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary btn-block">
                <i class="fa fa-search"></i>
                Buscar
            </button>
        </div>
    </div>
    <div id="adv_filters" style="<?= $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-8">
                <?= form_dropdown('status', $options_status, $filters['status'], 'class="form-control" title="Filtrar por Estado"'); ?>
            </div>
            <label for="status" class="col-md-4 control-label col-form-label">Estado compra</label>
        </div>
        <div class="form-group row">
            <div class="col-md-8">
                <?= form_dropdown('i', $options_institucion, $filters['i'], 'class="form-control form-control-chosen" title="Filtrar por institución"'); ?>
            </div>
            <label for="i" class="col-md-4 col-form-label">Institución</label>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <input name="fi" type="date" class="form-control" value="<?= $filters['fi'] ?>">
            </div>
            <div class="col-md-4">
                <input name="ff" type="date" class="form-control" value="<?= $filters['ff'] ?>">
            </div>
            <label for="i" class="col-md-4 col-form-label">Creada entre</label>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <input name="f1" type="date" class="form-control" value="<?= $filters['f1'] ?>">
            </div>
            <div class="col-md-4">
                <input name="f2" type="date" class="form-control" value="<?= $filters['f2'] ?>">
            </div>
            <label for="i" class="col-md-4 col-form-label">Actualizada entre</label>
        </div>
    </div>
</form>