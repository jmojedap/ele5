<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    $num_flipbook = 0;
        
    //Colores etiqueta
        $colores = $this->App_model->arr_color_area();
?>

<div class="row">
    <div class="col col-md-4">
        <?php $this->load->view('kits/form_buscar_elementos_v'); ?>
        
        <?php if ( count($busqueda) > 0 ){ ?>
            <ul class="list-group">
                <?php foreach ($resultados->result() as $row_flipbook) : ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col col-sm-6">
                                <span class="etiqueta primario"><?= $row_flipbook->id ?></span>
                                <?= anchor("flipbooks/paginas/{$row_flipbook->id}", $row_flipbook->nombre_flipbook, 'target="_blank" title="Ir al flipbook"') ?>
                            </div>
                            <div class="col col-sm-4">
                                <span class="etiqueta nivel"><?= $row_flipbook->nivel ?></span>
                                <?= $this->App_model->etiqueta_area($row_flipbook->area_id) ?>
                            </div>
                            <div class="col col-sm-2">
                                <?= anchor("kits/agregar_flipbook/{$row->id}/{$row_flipbook->id}/?{$busqueda_str}", '<i class="fa fa-plus"></i>', 'class="btn btn-default btn-sm pull-right" title="Agregar el contenido al kit"') ?>
                            </div>
                        </div>

                    </li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    </div>
    
    <div class="col col-md-8">
        <table class="table table-default bg-blanco">
            <thead>
                <th width="40px">No.</th>
                <th width="40px">ID</th>
                <th>Contenido</th>
                <th>Área</th>
                <th width="40px"></th>
            </thead>
            <tbody>
                <?php foreach ($flipbooks->result() as $row_flipbook) : ?>
                <?php
                    $num_flipbook += 1;
                ?>
                    <tr>
                        <td><?= $num_flipbook ?></td>
                        <td class="warning"><?= $row_flipbook->id ?></td>
                        <td><?= anchor("flipbooks/paginas/{$row_flipbook->id}", $row_flipbook->nombre_flipbook, 'target="_blank"') ?></td>
                        <td>
                            <span class="etiqueta nivel"><?= $row_flipbook->nivel ?></span>
                            <?= $this->App_model->etiqueta_area($row_flipbook->area_id) ?>
                        </td>
                        <td><?= anchor("kits/quitar_flipbook/{$row->id}/{$row_flipbook->asignacion_id}/?{$busqueda_str}", '<i class="fa fa-times"></i>', 'class="a4" title=""') ?></td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>