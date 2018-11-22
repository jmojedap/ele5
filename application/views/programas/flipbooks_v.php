<?php
    $num_flipbook = 0;
        
    //Colores etiqueta
        $colores = $this->App_model->arr_color_area();
?>

<p>Contenidos generados a partir del programa <span class="resaltar"><?= $row->nombre_programa ?></span>:</p>

<table class="table table-default bg-blanco">
    <thead>
        <th width="40px">No.</th>
        <th width="40px">ID</th>
        <th>Contenido</th>
        <th>Área</th>
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
            </tr>

        <?php endforeach ?>
    </tbody>
</table>
