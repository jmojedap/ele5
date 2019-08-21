<table class="table bg-white" cellspacing="0">
    <thead>
        <tr>
            <th width="40px">#</th>
            <th width="40px">Res</th>
            <th>Pregunta</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($respuestas_cuestionario->result() as $row_pregunta): ?>
        <?php
            $icono_resultado = '<i class="fa fa-check text-success"></i>';
            $clase_fila = 'success';
            if ( $row_pregunta->resultado == 0 )
            {
                $icono_resultado = '<i class="fa fa-times text-danger"></i>';
                $clase_fila = 'danger';
            }
        ?>

            <tr>
                <td><?= $row_pregunta->orden + 1 ?></td>
                <td class="<?= $clase_fila ?>"><?= $icono_resultado ?></td>
                <td><?= $row_pregunta->texto_pregunta ?></td>
            </tr>   
        <?php endforeach ?>
    </tbody>
</table>


    




