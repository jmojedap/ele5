<table class="table table-default bg-blanco">
    <thead>
        <th class="<?= $clases_col['accion'] ?>" width="30px"></th>
        <th class="<?= $clases_col['nombre_cuestionario'] ?>">Cuestionario</th>
    </thead>

    <tbody>
        <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
            <tr id="uc_<?= $row_cuestionario->uc_id ?>"> 
                <td class="<?= $clases_col['accion'] ?>">
                    <?php if ( $row_cuestionario->estado > 2 ) { ?>
                        <?= anchor("usuarios/resultados/{$row->id}/{$row_cuestionario->uc_id}", 'Resultados', 'class="btn btn-default" title=""') ?>
                    <?php } else { ?>
                        <?= anchor("cuestionarios/preliminar/{$row_cuestionario->uc_id}", 'Responder', 'class="btn btn-primary" title=""') ?>
                    <?php } ?>
                </td>
                <td class="<?= $clases_col['nombre_cuestionario'] ?>">
                    <?= $row_cuestionario->nombre_cuestionario ?>
                    <br/>
                    <?= $this->Item_model->nombre(151, $row_cuestionario->estado) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>