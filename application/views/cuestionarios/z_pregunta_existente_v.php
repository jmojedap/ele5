<?php

    $orden_mostrar = $orden + 1;

    //Formulario
    $att_form = array(
        'class' => 'form-horizontal'
    );
    
    $att_q = array(
        'class' =>  'form-control',
        'name' => 'q',
        'value' => $busqueda['q']
    );
    
    //Opciones de dropdowns
    //$opciones_nivel = $this->App_model->opciones_ref('nivel IS NOT NULL', 'nivel');
    $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Área');
    $opciones_componente = $this->Item_model->opciones_id('categoria_id = 8', 'Componente');
    //$opciones_tema = $this->App_model->opciones_tema('id > 0');
    
    
    $att_submit = array(
        'class' =>  'btn btn-primary',
        'value' =>  'Buscar'
    );

?>

<?php $this->load->view('cuestionarios/menu_agregar_pregunta_v'); ?>

<div class="row">
    <div class="col col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $preguntas->num_rows() ?> resultados      
            </div>
            <div class="panel-body">
                
                <?= form_open($destino_form, $att_form) ?>
                    <div class="form-group">
                        <label for="q" class="col-sm-3 control-label">Buscar</label>
                        <div class="col-sm-9">
                            <?= form_input($att_q) ?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label for="a" class="col-sm-3 control-label">Área</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('a', $opciones_area, $busqueda['a'], 'class="form-control"'); ?>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label for="a" class="col-sm-3 control-label">Nivel</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('n', $opciones_nivel, $busqueda['n'], 'title="Filtrar por nivel" class="form-control"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <?= form_submit($att_submit) ?>
                        </div>
                    </div>
                <?= form_close('') ?>
            </div>
        </div>
    </div>
    <div class="col col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                Agregar pregunta en la posición <span class="etiqueta informacion"><?= $orden_mostrar  ?></span> del cuestionario
            </div>
            <div class="panel-body">
                <?php if ( ! is_null($preguntas) ):?>
                
                <?php foreach ($preguntas->result() as $row_pregunta) : ?>
                        <div class="">
                            
                            
                            <span class="etiqueta nivel"><?= $row_pregunta->nivel ?></span>
                            <?= $this->App_model->etiqueta_area($row_pregunta->area_id) ?>
                            
                            <br/>
                            
                            <span class="suave">Cód. pregunta: </span>
                            <span class="resaltar"><?= $row_pregunta->cod_pregunta ?></span>

                            <p><?= $row_pregunta->texto_pregunta ?></p>
                            <ol style="list-style-type: upper-latin">
                                <li><?= $row_pregunta->opcion_1 ?></li>
                                <li><?= $row_pregunta->opcion_2 ?></li>
                                <li><?= $row_pregunta->opcion_3 ?></li>
                                <li><?= $row_pregunta->opcion_4 ?></li>
                            </ol>

                            <div class="div1">
                                <?= anchor("preguntas/insertar/{$row->id}/{$row_pregunta->id}/{$orden}/cuestionario", '<i class="fa fa-plus"></i>', 'class="btn btn-primary" title="Insertar la pregunta en la posición ' . $orden_mostrar . '"') ?>
                                <?= anchor("preguntas/detalle/{$row_pregunta->id}", 'Detalle', 'class="btn btn-default"') ?>
                            </div>
                        </div>
                        <hr/>

                    <?php endforeach ?>
                
                <?php endif?>
            </div>
        </div>
    </div>
</div>
