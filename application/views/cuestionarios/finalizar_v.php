<div class="alert alert-info" role="alert">
    El proceso de respuesta del cuestionario ha finalizado.
</div>

<table class="table bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th width="150px">Resumen</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Estudiante </td>
            <td><?= $nombre_usuario ?></td>
        </tr>
        <tr>
            <td>Cuestionario</td>
            <td><?= $row->nombre_cuestionario  ?></td>
        </tr>
        <tr>
            <td>Inicio respuesta</td>
            <td><?= $this->Pcrn->fecha_formato($row_uc->inicio_respuesta, 'Y-M-d h:i:s a')  ?></td>
        </tr>
        <tr>
            <td>Fin respuesta</td>
            <td><?= $this->Pcrn->fecha_formato($row_uc->fin_respuesta, 'Y-M-d h:i:s a')  ?></td>
        </tr>
        <tr>
            <td>Preguntas</td>
            <td><?= $row->num_preguntas ?></td>
        </tr>
        <tr>
            <td>Preguntas respondidas</td>
            <td><?= $row_uc->num_con_respuesta ?></td>
        </tr>

    </tbody>
</table>

<div class="div3">
    <?= anchor("usuarios/resultados/{$row_uc->usuario_id}/{$row_uc->id}", 'Ver resultados', 'class="btn btn-primary"') ?>
</div>
