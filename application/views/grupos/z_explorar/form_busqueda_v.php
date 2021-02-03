<?php
    $controlador = $this->uri->segment(1);

    $att_q = array(
        'class' =>  'form-control',
        'name' => 'q',
        'autofocus' => TRUE,
        'placeholder' => 'Buscar por nombre o descripción...',
        'value' => $busqueda['q']
    );
    
    $att_y = array(
        'class' =>  'form-control',
        'name' => 'y',
        'type' => 'number',
        'min' => 2012,
        'max' => 2020,
        'value' => $busqueda['y']
    );

    $att_submit = array(
        'class' =>  'btn btn-primary btn-block',
        'value' =>  'Buscar'
    );

    //Opciones de dropdowns
        $opciones_institucion = $this->App_model->opciones_institucion('id > 0', 'Todos');
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
        
        
    //Clases filtros
        $arr_filtros = array('i', 'n', 'y');
        foreach ( $arr_filtros as $filtro )
        {
            $clases_filtros[$filtro] = 'sin_filtrar';
            if ( strlen($busqueda[$filtro]) > 0 ) { $clases_filtros[$filtro] = ''; }
        }
?>

<?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
    <div class="">
        <div class="form-group">
            <div class="col-sm-10">
                <div class="input-group">
                    <?= form_input($att_q) ?>
                    <span class="input-group-btn" title="Mostrar búsqueda avanzada">
                        <button class="btn btn-info" id="alternar_avanzada" type="button">
                            <i class="fa fa-caret-down b_avanzada_si"></i>
                            <i class="fa fa-caret-up b_avanzada_no"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-sm-2">
                <?= form_submit($att_submit) ?>
            </div>
        </div>
        
        <div class="form-group <?= $clases_filtros['i'] ?>" style="<?php echo $style_filtros['i'] ?>">
            <label for="i" class="col-sm-3 control-label">Institución</label>
            <div class="col-sm-7">
                <?= form_dropdown('i', $opciones_institucion, $busqueda['i'], 'class="form-control chosen-select" title="Filtrar institución"'); ?>
            </div>
        </div>
        
        <div class="form-group <?= $clases_filtros['n'] ?>" style="<?php echo $style_filtros['n'] ?>">
            <label for="n" class="col-sm-3 control-label">Nivel</label>
            <div class="col-sm-7">
                <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'class="form-control chosen-select" title="Filtrar nivel"'); ?>
            </div>
        </div>
        
        <div class="form-group <?= $clases_filtros['y'] ?>" style="<?php echo $style_filtros['y'] ?>">
            <label for="y" class="col-sm-3 control-label">Año generación</label>
            <div class="col-sm-7">
                <?= form_input($att_y) ?>
            </div>
        </div>
    </div>

<?= form_close() ?>