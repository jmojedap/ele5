<?php
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
    $opciones_nivel = $this->App_model->opciones_nivel('item_largo');
    $opciones_area = $this->Item_model->opciones('categoria_id = 1');
    $opciones_componente = $this->Item_model->opciones('categoria_id = 8');
    
    $att_submit = array(
        'class' =>  'btn btn-primary',
        'value' =>  'Buscar'
    );

?>

<?php $this->load->view('admin/temas/agregar_pregunta_menu_v'); ?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Insertar pregunta existente - Buscar:
            </div>
            <div class="panel-body">
                <?= form_open("admin/temas/agregar_pregunta/{$row->id}/{$orden}", $att_form) ?>
                    <div class="form-group">
                        <label for="q" class="col-sm-3 control-label">Buscar</label>
                        <div class="col-sm-9">
                            <?= form_input($att_q) ?>
                        </div>
                    </div>

                    <div class="form-group hidden">
                        <label for="componente_id" class="col-sm-3 control-label">Componente</label>
                        <div class="col-sm-9">
                            <?= form_dropdown('componente_id', $opciones_componente, $busqueda['componente_id'], 'class="form-control"'); ?>
                        </div>
                    </div>

                    <?php  //Campos ocultos ?>
                    <?= form_hidden('area_id', $row->area_id) ?>
                    <?= form_hidden('nivel', $row->nivel) ?>
                
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <?= form_submit($att_submit) ?>
                        </div>
                    </div>

                <?= form_close('') ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <?php if ( ! is_null($preguntas) ):?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Resultados encontrados: <?= $preguntas->num_rows() ?>
                </div>
                
                <div class="panel-body">
                    
                        <!--Listado de preguntas encontradas-->
                        

                        <?php foreach ($preguntas->result() as $row_pregunta) : ?>

                            <?php
                                //Variables
                                $texto_tema = $this->App_model->nombre_tema($row_pregunta->tema_id);

                                if ( $row_pregunta->tema_id > 0 ) {
                                    //Si tiene tema asignado
                                    $texto_tema = anchor("admin/temas/preguntas/{$row_pregunta->tema_id}", $texto_tema);
                                }
                            ?>
                                <span class="suave">Cód. pregunta: </span>
                                <span class="resaltar"><?= $row_pregunta->cod_pregunta ?></span> | 
                                <span class="suave">Tema</span>
                                <span class="resaltar"><?= $texto_tema ?></span> | 
                                <span class="suave">Área</span>
                                <span class="resaltar"><?= $this->App_model->nombre_item($row_pregunta->area_id) ?></span> | 
                                <span class="suave">Nivel</span>
                                <span class="resaltar"><?php echo $this->Item_model->nombre(3, $row_pregunta->nivel) ?></span> | 

                                <p><?= $row_pregunta->texto_pregunta ?></p>
                                <ol style="list-style-type: upper-latin">
                                    <li><?= $row_pregunta->opcion_1 ?></li>
                                    <li><?= $row_pregunta->opcion_2 ?></li>
                                    <li><?= $row_pregunta->opcion_3 ?></li>
                                    <li><?= $row_pregunta->opcion_4 ?></li>
                                </ol>

                                <div class="sep1">
                                    <?= anchor("preguntas/insertar/{$row->id}/{$row_pregunta->id}/{$orden}/tema", "Insertar en {$orden_mostrar}", 'class="btn btn-default" title="Insertar la pregunta en la posición ' . $orden_mostrar . '"') ?><br/>
                                </div>
                            <hr/>
                        <?php endforeach ?>
                </div>
            </div>

        <?php endif ?>
    </div>
    
</div>




