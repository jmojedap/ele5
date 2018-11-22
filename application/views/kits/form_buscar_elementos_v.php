<?php
    
    //Formulario
        $att_form = array(
            'class' => 'form-horizontal'
        );

        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );

        //Opciones de dropdowns
        $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Seleccione el área');
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo', 'Seleccione el nivel');


        $att_submit = array(
            'class' =>  'btn btn-primary',
            'value' =>  'Buscar'
        );
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Buscar para agregar
    </div>
    <div class="panel-body">
        <?= form_open($destino_form, $att_form) ?>
            <div class="form-group">
                <label for="q" class="col-sm-3 control-label">Nombre</label>
                <div class="col-sm-9">
                    <?= form_input($att_q) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="area_id" class="col-sm-3 control-label">Área</label>
                <div class="col-sm-9">
                    <?= form_dropdown('a', $opciones_area, $busqueda['a'], 'class="form-control"'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="nivel" class="col-sm-3 control-label">Nivel</label>
                <div class="col-sm-9">
                    <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'class="form-control" title="Filtrar por nivel"'); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        <?= form_close('') ?>
    </div>
</div>