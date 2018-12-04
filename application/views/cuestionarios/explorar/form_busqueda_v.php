<?php
    $controlador = $this->uri->segment(1);

    $att_q = array(
        'class' =>  'form-control',
        'name' => 'q',
        'autofocus' => TRUE,
        'placeholder' => 'Buscar por nombre o descripción...',
        'value' => $busqueda['q']
    );

    $att_submit = array(
        'class' =>  'btn btn-primary btn-block',
        'value' =>  'Buscar'
    );

    //Opciones de dropdowns
        $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Área');
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo', 'Nivel');
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 15', 'Tipo');
        $opciones_institucion = $this->App_model->opciones_institucion('id > 0', 'Institución');
        
    //Clases filtros
        $arr_filtros = array('a', 'n', 'tp', 'i');
        foreach ( $arr_filtros as $filtro )
        {
            $clases_filtros[$filtro] = 'sin_filtrar';
            if ( strlen($busqueda[$filtro]) > 0 ) { $clases_filtros[$filtro] = ''; }
        }
?>

<?= form_open("cuestionarios/redirect_explorar/{$filtro_alcance}", $att_form) ?>
    <div class="form-horizontal">
        <div class="form-group row">
            <div class="col-md-4">
                <button type="button" class="btn btn-default btn-block">
                    Filtros <i class="fa fa-caret-down"></i>
                </button>
            </div>
            <div class="col-md-8">
                <div class="input-group">
                    <?php echo form_input($att_q) ?>
                    <div class="input-group-append" title="Mostrar búsqueda avanzada">
                        <button class="btn btn-primary input-group-addon">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group <?= $clases_filtros['a'] ?>">
            <label for="a" class="col-sm-3 control-label">Área</label>
            <div class="col-sm-7">
                <?= form_dropdown('a', $opciones_area, $busqueda['a'], 'class="form-control chosen-select" title="Filtrar por área"'); ?>
            </div>
        </div>
        <div class="form-group <?= $clases_filtros['n'] ?>">
            <label for="n" class="col-sm-3 control-label">Nivel</label>
            <div class="col-sm-7">
                <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'class="form-control chosen-select" title="Filtrar por nivel"'); ?>
            </div>
        </div>
        <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
            <div class="form-group <?= $clases_filtros['tp'] ?>">
                <label for="tp" class="col-sm-3 control-label">Tipo cuestionario</label>
                <div class="col-sm-7">
                    <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control chosen-select" title="Filtrar por tipo de cuestionario"'); ?>
                </div>
            </div>
            <div class="form-group <?= $clases_filtros['i'] ?>">
                <label for="i" class="col-sm-3 control-label">Institución</label>
                <div class="col-sm-7">
                    <?= form_dropdown('i', $opciones_institucion, $busqueda['i'], 'class="form-control chosen-select" title="Filtrar por institución"'); ?>
                </div>
            </div>
        <?php } ?>
        
    </div>

<?= form_close() ?>