<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<!-- SEPARADOR -->
<!--------------------------------------------------------------------------------->

<script>
// Document Ready
//-----------------------------------------------------------------------------

    $(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Porcentaje Respuestas Correctas'
        },
        subtitle: {
            text: 'Por grupo'
        },
        xAxis: {
            categories: [
                <?php foreach($grupos->result() as $row_grupo) : ?>
                    '<?= $row_grupo->nivel ?> - <?= $row_grupo->grupo ?>',
                <?php endforeach; ?>
            ],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: '% respuestas correctas',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: '%'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            name: '% correctas',
            data: [
                <?php foreach($grupos->result() as $row_grupo) : ?>
                    <?php
                        $busqueda['g'] = $row_grupo->id;
                        $res = $this->Cuestionario_model->res($busqueda);
                    ?>
                    <?= $res['porcentaje'] ?>,
                <?php endforeach; ?>
            ]
        }]
    });
});
</script>



<div class="row">
    <div class="col col-md-3">
    
    </div>
    <div class="col col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
              <div id="container" style="min-width: 360px; max-width: 1280px; height: 720px; margin: 0 auto;"></div>
            </div>
        </div>
        
    </div>
</div>

<table class="table table-default bg-blanco">
    <thead>
        <th class="<?= $clases_col['nombre_grupo'] ?>">Grupo</th>
        <th class="<?= $clases_col['respondidas_abs'] ?>">Respondidas</th>
        <th class="<?= $clases_col['correctas_abs'] ?>">Correctas</th>
        <th class="<?= $clases_col['porcentaje'] ?>">Porcentaje</th>
        <th class="<?= $clases_col['num_preguntas'] ?>">Num preguntas</th>
        <th class="<?= $clases_col['cant_asignados'] ?>">Asignados</th>
        <th class="<?= $clases_col['cant_respondieron'] ?>">Respondieron</th>
    </thead>
    
    <tbody>
        <?php foreach($grupos->result() as $row_grupo) : ?>
            <?php
                $busqueda['g'] = $row_grupo->id;
                $res = $this->Cuestionario_model->res($busqueda);
            ?>
            <tr>
                <td class="<?= $clases_col['nombre_grupo'] ?>">
                    <?= $row_grupo->nivel ?> - 
                    <?= $row_grupo->grupo ?>
                </td>
                <td>
                    <?= $res['respondidas_abs'] ?>
                </td>
                <td>
                    <?= $res['correctas_abs'] ?>
                </td>
                <td>
                    <?= $res['porcentaje'] ?>
                </td>
                <td>
                    <?= $res['num_preguntas'] ?>
                </td>
                <td>
                    <?= $res['cant_asignados'] ?>
                </td>
                <td>
                    <?= $res['cant_respondieron'] ?>
                </td>

            </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>

