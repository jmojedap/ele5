<?php
    $clase_area_todos = 'list-group-item';
    if ( $area_id == 0 ) { $clase_area_todos .= ' active'; }
    
    $clase_competencia_todos = 'list-group-item';
    if ( $competencia_id == 0 ) { $clase_competencia_todos .= ' active'; }

?>

<div class="row">
    <div class="col col-md-4">
        <h4>Áreas</h4>
        
        <div class="list-group" role="group">
            <?= anchor("cuestionarios/sugerencias/{$cuestionario_id}/0", 'Todos', 'class="'. $clase_area_todos .'"') ?>

            <?php foreach ($areas->result() as $row_area) : ?>
                <?php
                    $clase_link = 'list-group-item';
                    if ( $row_area->area_id == $area_id ) { $clase_link .= ' active'; }
                ?>
                <?= anchor("cuestionarios/sugerencias/{$cuestionario_id}/{$row_area->area_id}", $this->App_model->nombre_item($row_area->area_id), 'class="'. $clase_link .'"') ?>
            <?php endforeach ?>
        </div>
        
        <h4>Competencias</h4>
        
        <div class="list-group" role="group">
            <?= anchor("cuestionarios/sugerencias/{$cuestionario_id}/{$area_id}", 'Todos', 'class="'. $clase_competencia_todos .'"') ?>
            <?php foreach ($competencias->result() as $row_competencia) : ?>
                <?php
                    $clase_link = 'list-group-item';
                    if ( $row_competencia->competencia_id == $competencia_id ) { $clase_link .= ' active'; }
                ?>
                <?= anchor("cuestionarios/sugerencias/{$cuestionario_id}/{$area_id}/{$row_competencia->competencia_id}", $this->App_model->nombre_item($row_competencia->competencia_id), 'class="'. $clase_link .'"') ?>
            <?php endforeach ?>
        </div>
    </div>
    
    <div class="col col-md-8">
        <table class="table bg-blanco">
            <thead>
                <?php if ( $area_id == 0 ){ ?>
                    <th width="130px">Área</th>
                <?php } ?>
                
                <th>Competencia</th>
                <th width="150px">Rango resultado</th>
                <th>Tipo sugerencia</th>
                <th>Sugerencia</th>
            </thead>
            <tbody>
                <?php foreach ($sugerencias->result() as $row_sugerencia) : ?>
                    <tr>
                        
                        <?php if ( $area_id == 0 ){ ?>
                            <td><?= $this->App_model->nombre_item($row_sugerencia->area_id) ?></td>
                        <?php } ?>

                        <td><?= $this->App_model->nombre_item($row_sugerencia->competencia_id) ?></td>
                        <td><?php echo $row_sugerencia->rango . " - "  . $this->Item_model->nombre(154, $row_sugerencia->rango) ?></td>
                        <td><?php echo $this->Item_model->nombre(154, $row_sugerencia->rango, 'item_largo') ?></td>
                        <td><?= $row_sugerencia->texto_sugerencia ?></td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

