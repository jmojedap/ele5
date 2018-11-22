

<div class="sep1">
    <?= anchor("datos/reporte_temas_02/1", '<i class="fa fa-file-excel-o"></i> Descargar', 'class="btn btn-success" title="Descargar reporte en Excel"') ?>
</div>

<table class="table table-hover bg-blanco">
    <thead>
        <th>Programa ID</th>
        <th>Nombre programa</th>
        <th>Tema ID</th>
        <th>Nombre Tema</th>
        <th>Cód Tema</th>
        <th>Orden</th>
        
    </thead>
    <tbody>
        <?php foreach ($temas->result() as $row_tema) : ?>
            <tr>
                <td><?= $row_tema->programa_id ?></td>
                <td><?= $row_tema->nombre_programa ?></td>
                <td><?= $row_tema->tema_id ?></td>
                <td><?= $row_tema->nombre_tema ?></td>
                <td><?= $row_tema->cod_tema ?></td>
                <td><?= $row_tema->orden_tema ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td>Continúa...</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
    </tbody>
</table>