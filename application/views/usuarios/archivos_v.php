<?php


?>

<article class="module width_3_quarter">
    <header>
        <h3 class="rojo">Mis archivos</h3>    
    </header>
    
    
    
    <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <th>Descargar</th>
                    <th>Archivo</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos->result() as $row_archivo): ?>
                    <tr>
                        <td><?= anchor("assets/uploads/archivos/{$row_archivo->nombre_archivo}", 'Descargar', 'class="button white" target="_blank"') ?></td>
                        <td><?= $row_archivo->titulo_archivo ?></td>
                        <td><?= $row_archivo->descripcion ?></td>
                    </tr>
                <?php endforeach; //Recorriendo archivos ?>
            </tbody>
        </table>


    
    
</article>