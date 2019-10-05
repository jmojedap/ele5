<script>
    $(function () {
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Resultados respuestas a la pregunta'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || '#666'
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['Correctas',    <?= $resultado['cant_correctas'] ?>],
                    ['Incorrectas',     <?= $resultado['incorrectas'] ?>]
                ]
            }]
        });
    });
</script>

<div class="row">
    <div class="col col-md-4">
        <h3>Resultados pregunta</h3>
        <ul class="ul1">
            <li>
                <span class="etiqueta neutro w2"><?= $resultado['cant_respondidas'] ?></span>
                <i class="fa fa-users"></i>  Respondidas 
            </li>

            <li>
                <span class="etiqueta exito w2"><?= $resultado['cant_correctas'] ?></span>
                <i class="fa fa-check"></i>  Correctas 
            </li>

            <li>
                <span class="etiqueta alerta w2"><?= $resultado['incorrectas'] ?></span>
                <i class="fa fa-times"></i>  Incorrectas 
            </li>
        </ul>
    </div>
    <div class="col col-md-8">
        <div class="card">
            <div class="card-body">
                <div id="container">
                </div>
            </div>
        </div>
    </div>
</div>

