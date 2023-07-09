<?php
    $num_programa = 0;
?>

<div class="center_box_920">
    <p>
        El tema <span class="resaltar"><?= $row->nombre_tema ?></span> está incluido en 
        <span class="label label-info"> <?= $programas->num_rows() ?></span> programas.
    </p>
    
    <table class="table bg-white">
        <thead>
            <th class="warning" width="55px">ID</th>
            <th>Programa</th>
            <th>En la posición</th>
            <th>Usuario</th>
        </thead>
        <tbody>
            <?php foreach ($programas->result() as $row_programa) : ?>
                <tr>
                    <td class="warning"><?= $row_programa->id ?></td>
                    <td><?= anchor("programas/temas/{$row_programa->id}", $row_programa->nombre_programa, 'class=""') ?></td>
                    <td><?= $row_programa->orden + 1 ?></td>
                    <td><?= $this->App_model->nombre_usuario($row_programa->usuario_id, 2) ?></td>
                </tr>
                
            <?php endforeach ?>
        </tbody>
    </table>
</div>
