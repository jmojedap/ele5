<?php
    $num_registro = 0;
    $area_anterior_id = 0;
    $i = 0;
    
    $filtro_pregunta = "?n={$row->nivel}&a={$row->area_id}";
    
    //Permiso edición de preguntas
    $editar_preguntas = FALSE;
    if ( $this->session->userdata('rol_id') <= 2) { $editar_preguntas = TRUE; }
    if ( $row->tipo_id == 4 ) { $editar_preguntas = TRUE; }
?>
<div class="">
    
    <?php if ( $editar_preguntas ) : ?>                
        <p>
            <?php echo anchor("cuestionarios/pregunta_nueva/{$row->id}/0/add/{$filtro_pregunta}", 'Insertar pregunta al inicio', 'class="btn btn-secondary"') ?>
            <?php echo anchor("cuestionarios/pregunta_nueva/{$row->id}/{$preguntas->num_rows()}/add/{$filtro_pregunta}", 'Insertar pregunta al final', 'class="btn btn-secondary"') ?>
        </p>
    <?php endif ?>
    
    
    <table class="table bg-white">
        <thead>
            <th width="50px">No.</th>
            <th width="50px"></th>
            <th>Área</th>
            <th>Pregunta</th>
            <th class="hidden-xs hidden-sm">Tema</th>
            <th class="hidden-xs hidden-sm">Componente</th>
            <th class="hidden-xs hidden-sm">Competencia</th>
            <th class="hidden-xs" width="145px">Operaciones</th>
        </thead>
        <tbody>
            <?php foreach ($preguntas->result() as $row_pregunta) : ?>
            
                <?php
                    $num_subir = $num_registro - 1;
                    $num_bajar = $num_registro + 1;
                    $num_siguiente = $num_registro + 1;
                    
                    $nombre_tema = $this->App_model->nombre_tema($row_pregunta->tema_id);
                ?>
            
                <?php if ( $row_pregunta->area_id != $area_anterior_id ):?>
                    <?php $i = 1 ?>
                    <tr>
                        <td colspan="7" class="centrado">
                            <span class="resaltar">
                                <?php echo $this->App_model->nombre_item($row_pregunta->area_id); ?>
                            </span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $i += 1 ?>
                <?php endif ?>
                        
                <tr>
                    <td><?php echo $num_registro + 1 ?></td>
                    <td><?php echo $i ?></td>
                    <td><?php echo $this->App_model->etiqueta_area($row_pregunta->area_id) ?></td>
                    <td><?php echo $row_pregunta->texto_pregunta ?></td>
                    <td class="hidden-xs hidden-sm">
                        <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
                            <?php echo anchor("temas/leer/{$row_pregunta->tema_id}", $nombre_tema, 'class="" title=""') ?>
                        <?php } else { ?>
                            <?php echo $nombre_tema ?>
                        <?php } ?>
                    </td>
                    <td class="hidden-xs hidden-sm"><?php echo $this->App_model->nombre_item($row_pregunta->componente_id) ?></td>
                    <td class="hidden-xs hidden-sm"><?php echo $this->App_model->nombre_item($row_pregunta->competencia_id) ?></td>
                    <td class="hidden-xs">
                        
                        
                        <?php if ( $editar_preguntas ) : ?>                
                            
                            <?php echo anchor("cuestionarios/mover_pregunta/{$row->id}/{$row_pregunta->pregunta_id}/{$num_subir}", '<i class="fa fa-caret-up"></i>', 'class="a4" title="subir pregunta"') ?>
                            <?php echo anchor("cuestionarios/mover_pregunta/{$row->id}/{$row_pregunta->pregunta_id}/{$num_bajar}", '<i class="fa fa-caret-down"></i>', 'class="a4" title="bajar pregunta"') ?>
                            <?php echo anchor("cuestionarios/pregunta_nueva/{$row->id}/{$num_siguiente}/add", '<i class="fa fa-caret-right"></i>', 'class="a4" title="Insertar pregunta después de esta"') ?>
                            <?php echo anchor("preguntas/editar/{$row_pregunta->pregunta_id}", '<i class="fa fa-pencil-alt"></i>', 'class="a4" target="_blank" title="Detalle de la pregunta"') ?>
                            <?php echo $this->Pcrn->anchor_confirm("cuestionarios/quitar_pregunta/{$row->id}/{$row_pregunta->pregunta_id}", '<i class="fa fa-times"></i>', 'class="a4" title="Quitar pregunta de este cuestionario"', '¿Desea quitar esta pregunta del cuestionario?') ?>
                            
                        <?php endif ?>
                    </td>
                </tr>
                
                <?php
                    //Variables para el siguiente ciclo
                        $area_anterior_id =  $row_pregunta->area_id;
                        $num_registro += 1;
                ?>

            <?php endforeach ?>
        </tbody>
    </table>
</div>