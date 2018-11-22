<table class="table bg-blanco">
    <thead>
        <th>Nombre</th>
    </thead>
    <tbody>
        <?php foreach ($flipbooks->result() as $row_flipbook) : ?>
            <tr>
                <td><?= anchor("flipbooks/crear_cuestionario/{$row_flipbook->flipbook_id}", $this->App_model->nombre_flipbook($row_flipbook->flipbook_id), 'target="_blank"') ?></td>
            </tr>            
        <?php endforeach ?>
    </tbody>
</table>