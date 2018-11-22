<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th width="60px">Grupo</th>
            <th>Nivel - Área</th>
            <th>Año</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grupos->result() as $row_grupo): ?>
            <tr>
                <td><?= anchor("grupos/profesores/{$row_grupo->grupo_id}", $row_grupo->nombre_grupo, 'class="btn btn-primary w3"') ?></td>
                <td>
                    <span class="etiqueta nivel w1"><?= $row_grupo->nivel ?></span>
                    <?= $this->App_model->etiqueta_area($row_grupo->area_id) ?>
                </td>
                <td><?= $row_grupo->anio_generacion ?></td>
            </tr>
        <?php endforeach; //Recorriendo grupos ?>
    </tbody>
</table>