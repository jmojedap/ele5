<?php $this->load->view('head_includes/highcharts') ?>
<?php $this->load->view('head_includes/grafico_competencias') ?>

<?php
    $fecha_fin = $this->Pcrn->fecha_formato($row_uc->fecha_fin);
    $tiempo_hace = $this->Pcrn->tiempo_hace($row_uc->fecha_fin);
    
    $i = 0;
    $total_estudiantes = 0;
    $total_correctas = 0;
    $total_incorrectas = 0;
    $total_preguntas = 0;
    
    $clases_rango = array(
        0 => '',
        1 => 'rango_bajo',
        2 => 'rango_medio_bajo',
        3 => 'rango_medio_alto',
        4 => 'rango_alto'
    );
?>

<table class="table bg-white" cellspacing="0"> 
    <thead>
        <tr>
            <th width="300px">Competencia</th>
            <th><i class="fa fa-question"></i></th>
            <th><i class="fa fa-check text-success"></i></th>
            <th><i class="fa fa-times text-danger"></i></th>
            <th>%</th>
            <th width="150px">Resultado</th>
            <th>Estrategia</th>
            <th>Sugerencia</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($competencias->result() as $row_2) : ?>

            <?php
            //Variables
            $resultados_competencia = $resultados[$row_2->competencia_id];
            $porcentaje = $resultados_competencia['porcentaje'] . "%";
            $total_estudiantes += $resultados_competencia['num_usuarios'];
            $total_correctas += $resultados_competencia['correctas'] * $resultados_competencia['num_usuarios'];
            $total_incorrectas += $resultados_competencia['incorrectas'] * $resultados_competencia['num_usuarios'];
            $total_preguntas += $resultados_competencia['num_preguntas'];

            //Rango resultados
            $rango = $this->App_model->rango_cuestionarios($porcentaje / 100);

            //Sugerencia
            $condicion = "cuestionario_id = {$row_uc->cuestionario_id} AND ";
            $condicion .= "competencia_id = {$row_2->competencia_id} AND ";
            $condicion .= "rango = {$rango}";

            $clase_rango = '';
            if ($rango > 0) {
                $clase_rango = $clases_rango[$rango];
            }
            ?>

            <tr>
                <td><?php echo $this->App_model->nombre_item($row_2->competencia_id, 1) ?></td>
                <td><?php echo $resultados_competencia['num_preguntas'] ?></td>
                <td class="table-success"><?php echo $resultados_competencia['correctas'] ?></td>
                <td><?php echo $resultados_competencia['incorrectas'] ?></td>
                <td><?php echo $porcentaje ?></td>
                <td>
                    <div class="table-<?php echo $clase_rango ?>">
                        <?php echo $this->Item_model->nombre(154, $rango) ?>
                    </div>
                </td>
                <td><?php echo $this->Item_model->nombre(154, $rango, 'item_largo') ?></td>
            </tr>

            <?php $i = $i + 1 ?>
        <?php endforeach; //Recorriendo áreas ?>
    </tbody>

    <tfoot>
        <tr class="total">
            <td>Total</td>
            <td><?php echo $total_preguntas ?></td>
            <td class="table-success"><?php echo number_format($total_correctas, 0) ?></td>
            <td><?php echo number_format($total_incorrectas, 0) ?></td>
            <td><span class="resaltar"><?php echo number_format(100 * ($total_correctas) / $this->Pcrn->no_cero($total_correctas + $total_incorrectas), 0) . "%" ?></span></td>
            <td colspan="3"></td>
        </tr>
    </tfoot>
</table>

<div class="card">
    <div class="card-header">
        Comparativo de competencias
    </div>
    <div class="card-body">
        <div id="container_1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
</div>