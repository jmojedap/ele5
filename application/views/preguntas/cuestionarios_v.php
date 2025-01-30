<div class="center_box_750">
    <table class="table bg-white">
        <thead>
            <th>Cuestionario</th>
            <th>Área</th>
        </thead>
        <tbody>
            <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
                <tr>
                    <td><?= anchor("cuestionarios/preguntas/{$row_cuestionario->cuestionario_id}", $row_cuestionario->nombre_cuestionario) ?></td>
                    <td>
                        <span class="etiqueta nivel w1"><?= $row_cuestionario->nivel ?></span>
                        <?= $this->App_model->etiqueta_area($row_cuestionario->area_id) ?>
                    </td>
                </tr>
                
            <?php endforeach ?>
        </tbody>
    </table>
</div>