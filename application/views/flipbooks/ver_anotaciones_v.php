<p>
    <?= anchor("flipbooks/anotaciones/$row->id", "Volver", 'class="a2" title"Todas las anotaciones"') ?>
</p>
<p class="p1">
    <span class="suave">Estudiante:</span>
    <span class="resaltar"> <?= anchor("usuarios/flipbooks/{$usuario_id}", $nombre_usuario) ?></span> |
    <span class="suave">Anotaciones: </span>
    <span class="resaltar"><?= $anotaciones->num_rows() ?></span>
</p>
    
<table class="tablesorter" cellspacing="0">
    <thead>
        <tr>
            <th colspan="2">Pág</th>
            <th>Título</th>
            <th>Anotación</th>
            <th>Fecha</th>


        </tr>
    </thead>
    <tbody>
        <?php foreach ($anotaciones->result() as $row_anotacion): ?>

        <?php
            $row_pf = $this->Pcrn->registro('pagina_flipbook', "id = {$row_anotacion->pagina_id}");
            $img_pf = $this->Pagina_model->img_pf($row_pf, 2);
        ?>
            <tr>
                <td><?= $row_anotacion->num_pagina ?></td>
                <td><?= $img_pf ?></td>
                <td><?= $row_pf->titulo_pagina ?></td>
                <td><?= $row_anotacion->anotacion ?></td>
                <td><?= $row_anotacion->editado ?></td>
            <tr/>   
        <?php endforeach ?>
    </tbody>
</table>