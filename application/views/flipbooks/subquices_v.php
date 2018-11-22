<div class="row">
    <div class="col col-md-6">
        <table class="table table-default bg-blanco">
            <thead>
                <th class="<?= $clases_col['num_pag'] ?>">Pág</th>
                <th class="<?= $clases_col['tema_id'] ?>">Quiz ID</th>
                <th class="<?= $clases_col['nombre_quiz'] ?>">Nombre quiz</th>
            </thead>

            <tbody>
                <?php foreach($quices as $quiz) : ?>
                <tr>
                    <td class="<?= $clases_col['num_pag'] ?>">
                        <?= $quiz['num_pagina'] ?>
                    </td>
                    <td class="<?= $clases_col['id'] ?>">
                        <?= $quiz['id'] ?>
                    </td>
                    <td class="<?= $clases_col['nombre_quiz'] ?>">
                        <?= $quiz['nombre_quiz'] ?>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
        
    </div>
    
    <div class="col col-md-6">
        
    </div>
</div>