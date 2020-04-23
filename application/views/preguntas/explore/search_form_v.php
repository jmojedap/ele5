<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;

    //Opciones versión propuesta
    $options_version = array(
        '00' => ' [ Todas las preguntas ]',
        '01' => 'Con versión propuesta'
    );
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
                <?php echo form_dropdown('tp', $options_tipo, $filters['tp'], 'class="form-control" title="Filtrar por tipo" v-model="filters.tp"'); ?>
            </div>
            <label for="tp" class="col-md-3 col-form-label">Tipo pregunta</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('f2', $options_difficulty_level, $filters['f2'], 'class="form-control" title="Filtrar por nivel de dificultad" v-model="filters.f2"'); ?>
            </div>
            <label for="f2" class="col-md-3 col-form-label">Nivel de dificultad</label>
        </div>
        <?php if ( $this->session->userdata('role') <= 2 ) { ?>
            <div class="form-group row">
                <div class="col-md-9">
                    <?php echo form_dropdown('f1', $options_version, $filters['f1'], 'class="form-control" title="Filtrar por estado versión" v-model="filters.f1"'); ?>
                </div>
                <label for="f1" class="col-md-3 col-form-label">Estado versión</label>
            </div>
        <?php } ?>
        <div class="form-group row">
            <div class="col-md-5">
                <?php echo form_dropdown('o', $options_order, $filters['o'], 'class="form-control" title="Ordenar por" v-model="filters.o"'); ?>
            </div>
            <div class="col-md-4">
                <?php echo form_dropdown('ot', $options_order_type, $filters['ot'], 'class="form-control" title="Tipo de orden" v-model="filters.ot"'); ?>
            </div>
            <label for="o" class="col-md-3 col-form-label">Ordenar por</label>
        </div>
    </div>
</form>