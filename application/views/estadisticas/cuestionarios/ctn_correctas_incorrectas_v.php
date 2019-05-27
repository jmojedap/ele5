<?php $this->load->view($vista_submenu) ?>

<!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div class="row">
    <div class="col-md-9">
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-md-3">
        <?php if ( count($campos_filtros) > 0 ) { ?>
            <?php $this->load->view('estadisticas/form_filtros_v'); ?>
        <?php } ?>
    </div>
</div>  


<script>
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Total Correctas e Incorrectas por Mes'
    },
    xAxis: {
        categories: [
            <?php foreach ( $serie->result() as $row_mes ) { ?>
                '<?php echo $row_mes->mes ?>',
            <?php } ?>
        ]
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidad de respuestas'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
    series: [{
        name: 'Incorrectas',
        data: [
            <?php foreach ( $serie->result() as $row_mes ) { ?>
                <?php echo ($row_mes->sum_cant_respondidas - $row_mes->sum_cant_correctas) ?>,
            <?php } ?>
        ],
        color: '#d9534f'
    }, {
        name: 'Correctas',
        data: [
            <?php foreach ( $serie->result() as $row_mes ) { ?>
                <?php echo $row_mes->sum_cant_correctas ?>,
            <?php } ?>
        ],
        color: '#7cb5ec'
    }]
});
</script>

