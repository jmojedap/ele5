<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th>Grupo</th>
            <th>Año</th>
            <th>Nivel</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grupos->result() as $row_grupo): ?>
            <tr>
                <td><?= anchor("grupos/estudiantes/{$row_grupo->id}", $row_grupo->nombre_grupo, 'class="btn btn-primary"') ?></td>
                <td><?= $row_grupo->anio_generacion ?></td>
                <td><?= $row_grupo->nivel ?></td>
            </tr>
        <?php endforeach; //Recorriendo grupos ?>
    </tbody>
</table>