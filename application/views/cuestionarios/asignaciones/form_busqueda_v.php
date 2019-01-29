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
        $opciones_estado = $this->Item_model->opciones('categoria_id = 151', 'Estado');
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 15', 'Tipo cuestionario');
        
    //Clases filtros
        $arr_filtros = array('est', 'tp');
        foreach ( $arr_filtros as $filtro )
        {
            $clases_filtros[$filtro] = 'sin_filtrar';
            if ( strlen($busqueda[$filtro]) > 0 ) { $clases_filtros[$filtro] = ''; }
        }
?>

<?= form_open("busquedas/redirect/cuestionarios/asignaciones", $att_form) ?>
    <div class="form-horizontal">
        <div class="form-group row">
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
        
        <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
            <div class="form-group row <?= $clases_filtros['est'] ?>">
                <label for="tp" class="col-sm-3 control-label">Estado respuesta</label>
                <div class="col-sm-7">
                    <?= form_dropdown('est', $opciones_estado, $busqueda['est'], 'class="form-control" title="Filtrar por estado respuesta"'); ?>
                </div>
            </div>
            <div class="form-group row <?= $clases_filtros['tp'] ?>">
                <label for="i" class="col-sm-3 control-label">Tipo cuestionario</label>
                <div class="col-sm-7">
                    <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control" title="Filtrar por tipo cuestionario"'); ?>
                </div>
            </div>
        <?php } ?>
        
    </div>

<?= form_close() ?>