<?php
    $num_cuestionario = 0;
        
    //Colores etiqueta
        $colores = $this->App_model->arr_color_area();
?>

<div class="row">
    <div class="col col-md-4">
        <?php $this->load->view('kits/form_buscar_elementos_v'); ?>
        
        <?php if ( count($busqueda) > 0 ){ ?>
            <ul class="list-group">
                <?php foreach ($resultados->result() as $row_cuestionario) : ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col col-sm-6">
                                <span class="etiqueta primario"><?= $row_cuestionario->id ?></span>
                                <?= anchor("cuestionarios/preguntas/{$row_cuestionario->id}", $row_cuestionario->nombre_cuestionario, 'target="_blank" title="Ir al cuestionario"') ?>
                            </div>
                            <div class="col col-sm-4">
                                <span class="etiqueta nivel"><?= $row_cuestionario->nivel ?></span>
                                <?= $this->App_model->etiqueta_area($row_cuestionario->area_id) ?>
                            </div>
                            <div class="col col-sm-2">
                                <?= anchor("kits/agregar_cuestionario/{$row->id}/{$row_cuestionario->id}/?{$busqueda_str}", '<i class="fa fa-plus"></i>', 'class="btn btn-light btn-sm pull-right" title="Agregar el cuestionario al kit"') ?>
                            </div>
                        </div>

                    </li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    </div>
    <div class="col col-md-8">
        <table class="table table-default bg-white">
            <thead>
                <th width="40px">No.</th>
                <th width="40px">ID</th>
                <th>Cuestionario</th>
                <th>Área</th>
                <th width="40px"></th>
            </thead>
            <tbody>
                <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
                <?php
                    $num_cuestionario += 1;
                ?>
                    <tr>
                        <td><?= $num_cuestionario ?></td>
                        <td class="warning"><?= $row_cuestionario->id ?></td>
                        <td><?= anchor("cuestionarios/paginas/{$row_cuestionario->id}", $row_cuestionario->nombre_cuestionario, 'target="_blank"') ?></td>
                        <td>
                            <span class="etiqueta nivel w1"><?= $row_cuestionario->nivel ?></span>
                            <?= $this->App_model->etiqueta_area($row_cuestionario->area_id) ?>
                        </td>
                        <td><?= anchor("kits/quitar_cuestionario/{$row->id}/{$row_cuestionario->asignacion_id}/?{$busqueda_str}", '<i class="fa fa-times"></i>', 'class="a4" title=""') ?></td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>