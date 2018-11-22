<table class="table table-hover bg-blanco">
    <thead>
        <th class="<?= $clases_col['id'] ?> warning" width="40px">ID</th>
        <th class="<?= $clases_col['nombre_tema'] ?>">Tema</th>
        <th class="<?= $clases_col['nivel_area'] ?>">Nivel Área</th>
    </thead>
    
    <tbody>
        <?php foreach($temas->result() as $row_tema) : ?>
        <tr>
            <td class="<?= $clases_col['id'] ?> warning">
                <?= $row_tema->tema_id ?>
            </td>
            <td class="<?= $clases_col['nombre_tema'] ?>">
                <?= anchor("temas/preguntas/{$row_tema->tema_id}", $row_tema->nombre_tema, 'class="" title=""') ?>
            </td>
            <td>
                <span class="etiqueta nivel w1"><?= $row_tema->nivel ?></span>
                <?= $this->App_model->etiqueta_area($row_tema->area_id) ?>
            </td>
        </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>