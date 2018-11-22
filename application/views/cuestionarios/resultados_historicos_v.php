<?php
    //Array porcentajes
    $porcentajes = array();
    foreach ($cuestionarios->result() as $row_cuestionario){
        $datos_cuestionario = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);
        $porcentaje = 100 * $row_cuestionario->correctas / $this->Pcrn->no_cero($datos_cuestionario->num_preguntas);
        $porcentajes[] = number_format($porcentaje, 1);
    }
?>

<script>
$(function () {
        $('#container').highcharts({
            chart: {
            },
            title: {
                text: 'Resultados cuestionarios'
            },
            subtitle: {
                text: 'Nombre de la estudiante'
            },
            xAxis: {
                categories: [
                    <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
                        '<?= $row_cuestionario->nombre_cuestionario . "', " ?>
                    <?php endforeach ?>
                ]
            },
            yAxis: {
                title: {
                    text: 'Porcentaje'
                },
                max: 100
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                type: 'column',
                name: '% correctas',
                fillcolor: '#428bca',
                data: [
                    <?php foreach ($porcentajes as $porcentaje) : ?>
                        <?= $porcentaje . ", " ?>
                    <?php endforeach ?>
                ]
            }, {
                type: 'spline',
                name: 'Tendencia',
                lineColor: '#CA4D75',
                data: [
                    <?php foreach ($porcentajes as $porcentaje) : ?>
                        <?= $porcentaje . ", " ?>
                    <?php endforeach ?>
                ],
                marker: {
                	lineWidth: 2,
                	lineColor: '#CA4D75',
                	fillColor: 'white'
                }
            }]
        });
    });
</script>

<div class="seccion group">
    <div class="col col_box span_2_of_2">
        <div class="info_container_body">
            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</div>

<div class="section group">
    <div class="col col_box span_3_of_3">
        <div class="info_container_body">
            <h2>Resultados históricos</h2>
            
            <table class="tablesorter">
                <thead>
                    <th>Cuestionario</th>
                    <th>Fecha</th>
                    <th>Preguntas</th>
                    <th>Respondidas</th>
                    <th>Correctas</th>
                    <th>Porcentaje</th>
                </thead>
                <tbody>
                    <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
                    <?php
                        $datos_cuestionario = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);
                        $porcentaje = 100 * $row_cuestionario->correctas / $this->Pcrn->no_cero($datos_cuestionario->num_preguntas);
                        $porcentaje = number_format($porcentaje, 1);
                    ?>
                    
                        <tr>
                            <td><?= $row_cuestionario->nombre_cuestionario ?></td>
                            <td><?= $this->Pcrn->fecha_formato($row_cuestionario->fin_respuesta, 'Y-M-d') ?></td>
                            <td><?= $datos_cuestionario->num_preguntas ?></td>
                            <td><?= $row_cuestionario->respondidas ?></td>
                            <td><?= $row_cuestionario->correctas ?></td>
                            <td><?= $porcentaje; ?></td>
                        </tr>

                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>