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
                    <button type="button" class="btn btn-light btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
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
                <?php echo form_dropdown('a', $options_area, $filters['a'], 'class="form-control" title="Filtrar por área"'); ?>
            </div>
            <label for="a" class="col-md-3 control-label col-form-label">Área</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('n', $options_nivel, $filters['n'], 'class="form-control" title="Filtrar por nivel"'); ?>
            </div>
            <label for="n" class="col-md-3 control-label col-form-label">Nivel</label>
        </div>
        <?php if ( $this->session->userdata('role') <= 2 ) : ?>
            <div class="form-group row">
                <div class="col-md-9">
                    <?php echo form_dropdown('tp', $options_tipo, $filters['tp'], 'class="form-control" title="Filtrar por tipo"'); ?>
                </div>
                <label for="tp" class="col-md-3 control-label col-form-label">Tipo cuestionario</label>
            </div>
            <div class="form-group row">
                <div class="col-md-9">
                    <?php echo form_dropdown('i', $options_institucion, $filters['i'], 'class="form-control form-control-chosen" title="Filtrar por institución"'); ?>
                </div>
                <label for="i" class="col-md-3 control-label col-form-label">Institución</label>
            </div>
            <div class="form-group row">
                <div class="col-md-9">
                    <input type="date" name="fi" class="form-control" title="Creados a partir de esta fecha" value="<?= $filters['fi'] ?>">
                </div>
                <label for="fi" class="col-md-3 control-label col-form-label">Creado desde</label>
            </div>
            <div class="form-group row">
                <div class="col-md-9">
                    <input type="date" name="ff" class="form-control" title="Creados hasta esta fecha" value="<?= $filters['ff'] ?>">
                </div>
                <label for="ff" class="col-md-3 control-label col-form-label">Creado hasta</label>
            </div>
        <?php endif; ?>
    </div>
</form>