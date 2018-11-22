<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    //Elementos formulario
        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'autofocus' => TRUE,
            'placeholder' => 'Buscar por nombre...',
            'value' => $busqueda['q']
        );

        $att_submit = array(
            'class' =>  'btn btn-primary btn-block',
            'value' =>  'Buscar'
        );
        
    //Clases filtros
        foreach ( $arr_filtros as $filtro )
        {
            $clases_filtros[$filtro] = 'filtro';
            if ( strlen($busqueda[$filtro]) > 0 ) { $clases_filtros[$filtro] = ''; }
        }
?>

<?= form_open("app/buscar/{$controlador}/{$funcion}", $att_form) ?>
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-9">
                <div class="input-group">
                    <?= form_input($att_q) ?>
                    <span class="input-group-btn" title="Mostrar búsqueda avanzada">
                        <button class="btn btn-info" id="alternar_avanzada" type="button">
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="col-sm-3">
                <?= form_submit($att_submit) ?>
            </div>
        </div>
        
        <div class="form-group <?= $clases_filtros['tp'] ?>">
            <label for="rol" class="col-sm-3 control-label">Tipo</label>
            <div class="col-sm-6">
                <?= form_dropdown('tp', $opciones_tipo, $busqueda['tp'], 'class="form-control chosen-select" title="Filtrar por tipo de post"'); ?>
            </div>
        </div>
    </div>

<?= form_close() ?>