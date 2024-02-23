<h2>tabla: quiz</h2>
<table class="table table-hover bg-white">
    <thead>
        <th>id</th>
        <th>nombre_quiz</th>
        <th>cod_quiz</th>
        <th>area_id</th>
        <th>nivel</th>
        <th>texto_enunciado</th>
        <th>tipo_quiz_id</th>
        <th>clave</th>
        <th>usuario_id</th>
        <th>editado</th>
    </thead>
    <tbody>
        <tr>
            <td><?= $row->id ?></td>
            <td><?= $row->nombre_quiz?></td>
            <td><?= $row->cod_quiz?></td>
            <td><?= $row->area_id?></td>
            <td><?= $row->nivel?></td>
            <td><?= $row->texto_enunciado?></td>
            <td><?= $row->tipo_quiz_id?></td>
            <td><?= $row->clave?></td>
            <td><?= $row->usuario_id?></td>
            <td><?= $row->editado?></td>
        </tr>
    </tbody>
</table>



<h2>tabla: quiz_detalle (<?php echo $quiz_elementos->num_rows() ?>)</h2>

<table class="table table-bordered bg-white">
    <thead>
        <th>id</th>
        <th>quiz_id</th>
        <th>tipo_id</th>
        <th>orden</th>
        <th>texto</th>
        <th>clave</th>
        <th>archivo</th>
        <th>detalle</th>
        <th>x</th>
        <th>y</th>
        
    </thead>
    <tbody>
        <tr style="background: #DDD;">
            <td></td>
            <td>índice quiz</td>
            <td>tipo de elemento</td>
            <td></td>
            <td></td>
            <td></td>
            <td>nombre archivo</td>
            <td></td>
            <td></td>
            <td></td>
            
        </tr>
        <?php foreach ( $quiz_elementos->result() as $row_elemento) { ?>
            <tr>
                <td><?= $row_elemento->id ?></td>
                <td><?= $row_elemento->quiz_id ?></td>
                <td><?= $row_elemento->tipo_id ?></td>
                <td><?= $row_elemento->orden ?></td>
                <td><?= $row_elemento->texto ?></td>
                <td><?= $row_elemento->clave ?></td>
                <td><?= $row_elemento->archivo ?></td>
                <td><?= $row_elemento->detalle ?></td>
                <td><?= $row_elemento->x ?></td>
                <td><?= $row_elemento->y ?></td>
            </tr>
            
        <?php } ?>
    </tbody>
</table>