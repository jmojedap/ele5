<?php
    $att_form = array(
        'class' =>  'form1'
    );
    
    $att_notas = array(
        'name' =>   'notas',
        'class' => 'textarea1',
        'rows' => 3,
        'value' => set_value('notas')
        
    );
    
    $att_submit = array(
        'value' =>  'Agregar',
        'class' => 'button orange'
    );
            
    $opciones_valor_evento = $this->App_model->opciones_item('categoria_id = 17');
?>

<div class="section group">
    <div class="col col_box span_1_of_3">
        
        <div class="info_container_body">
            <?= form_open("instituciones/agregar_anotacion/{$institucion_id}", $att_form) ?>
            
            <div class="div1">
                <label for="notas" class="label1">Anotación</label>
                <p class="descripcion">Anotación sobre esta institución</p>
                <?= form_textarea($att_notas) ?><br/>
                <?= date('Y-m-d H:i:s') ?>
            </div>
            <div class="div1">
                <label for="valor_evento" class="label1">Estado</label>
                <p class="descripcion">Los estados para las anotaciones de la bitácora pueden agregarse/editarse en panel de control > parámetros</p>
                <?= form_dropdown('valor_evento', $opciones_valor_evento, set_value('valor_evento') ) ?>
            </div>
            
            <div class="div1">
                <?= form_submit($att_submit) ?>
            </div>
                
            <?= form_close('') ?>
        </div>
        
        <?php if ( validation_errors() ):?>
            <hr/>
            <div class="div1">
                <?= validation_errors('<h4 class="alert_error">', '</h4>') ?>
            </div>
        <?php endif ?>
        
    </div>
    
    <div class="col span_2_of_3">
            
        
    
        <div class="info_container_body">
            <div class="div1" style="text-align: center;">
                <?= $this->pagination->create_links(); ?>
            </div>
            <table class="tablesorter">
                <thead>
                    <th>Anotación</th>
                    <th width="20"></th>
                </thead>
                <tbody>
                    <tr>
                        
                    </tr>
                    <?php foreach ($anotaciones->result() as $row_anotacion) : ?>
                        <tr>
                            <td>
                                <span class="resaltar"><?= $this->App_model->nombre_usuario($row_anotacion->usuario_id, 2) ?></span><br/>
                                <?= $this->Pcrn->fecha_formato($row_anotacion->fecha_evento, 'Y-M-d') ?> | Hace <?= $this->Pcrn->tiempo_hace($row_anotacion->fecha_evento) ?><br/>
                                <?= $this->App_model->nombre_item($row_anotacion->valor_evento) ?>
                                <p><?= $row_anotacion->notas ?></p>
                            </td>
                            <td><?= $this->Pcrn->anchor_confirm("instituciones/eliminar_bitacora/{$row_anotacion->id}/{$row->id}", '<i class="fa fa-trash-o"></i>', 'class="a2" title"Eliminar anotación"') ?></td>
                        </tr>

                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    