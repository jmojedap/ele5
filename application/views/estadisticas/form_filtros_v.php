<?php

    $att_form = array(
        'class' => 'form form-horizontal'
    );

    $att_fi = array(
        'id'     => 'fecha_inicial',
        'name'   => 'fi',
        'type' => 'date',
        'value' => $filtros['fi'],
        'class'  => 'form-control',
        'placeholder'   => 'Desde'
    );

    $att_ff = array(
        'id'     => 'fecha_final',
        'name'   => 'ff',
        'type' => 'date',
        'class'  => 'form-control',
        'value' => $filtros['ff'],
        'placeholder'   => 'Hasta'
    );
    
    //Opciones de dropdowns
    $opciones_institucion = $this->App_model->opciones_institucion('id > 0', 'Todos');
    $opciones_nivel = $this->App_model->opciones_nivel('item_largo', 'Todos');
    $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Área');
    $opciones_fecha_atras = $this->Busqueda_model->opciones_fecha_atras();

    $att_submit = array(
        'class' =>  'btn btn-primary',
        'value' =>  'Filtrar'
    );
    
    $funcion = $this->uri->segment(2);
    
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Filtros
    </div>
    <div class="panel-body" style="min-height: 500px;">
        <div style="padding-right: 0px">
            <?= form_open($destino_form, $att_form) ?>
                <?php if ( in_array('institucion', $campos_filtros) ) { ?>
                    <div class="form-group">
                        <label for="i" class="col-sm-3 control-label">Institución</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('i', $opciones_institucion, $filtros['i'], 'class="form-control chosen-select"'); ?>
                        </div>
                    </div>
                <?php } ?>
            
                <?php if ( in_array('nivel', $campos_filtros) ) { ?>
                    <div class="form-group">
                        <label for="n" class="col-sm-3 control-label">Nivel</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('n', $opciones_nivel, $filtros['n'], 'class="form-control chosen-select"'); ?>
                        </div>
                        
                    </div>
                <?php } ?>
            
                <?php if ( in_array('area', $campos_filtros) ) { ?>
                    <div class="form-group">
                        <label for="fa" class="col-sm-3 control-label">Área</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('a', $opciones_area, $filtros['a'], 'class="form-control chosen-select"'); ?>
                        </div>
                    </div>
                <?php } ?>
            
                <?php if ( in_array('fecha_atras', $campos_filtros) ) { ?>
                    <div class="form-group">
                        <label for="fa" class="col-sm-3 control-label">Intervalo</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('fa', $opciones_fecha_atras, $filtros['fa'], 'class="form-control chosen-select"'); ?>
                        </div>
                        
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="n" class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <?= form_submit($att_submit) ?>
                        <?= anchor("estadisticas/{$funcion}", 'Total', 'class="btn btn-default"') ?>
                    </div>
                </div>
            <?= form_close('') ?>
        </div>
        
        <?php if ( ! is_null($destino_exportar) ) { ?>
            <hr/>
            <?= anchor("{$destino_exportar}?{$filtros_str}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success"') ?>
        <?php } ?>
        
        
        
    </div>
</div>