<?php
    $suma_cant_login = 0;
    foreach ($serie->result() as $row_nivel) 
    {
        $suma_cant_login += $row_nivel->cant_eventos;
    }
    
    $avg_cant_login = $this->Pcrn->dividir($suma_cant_login, $serie->num_rows());
?>

<script>
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Login de usuarios por nivel'
        },
        xAxis: {
            categories: [
                <?php foreach($serie->result() as $row_nivel) : ?>
                    '<?= $this->Item_model->nombre(3, $row_nivel->nivel); ?>',
                <?php endforeach; ?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad de login'
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
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: [{
            name: 'Login de usuarios',
            data: [
                <?php foreach($serie->result() as $row_nivel) : ?>
                    <?= $row_nivel->cant_eventos ?>,
                <?php endforeach; ?>
            ]
        }]
    });
});
</script>

<?php $this->load->view($vista_submenu); ?>

<div class="row">
    <div class="col col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
        
        <table class="table table-default bg-blanco">
            <tbody>
                <tr>
                    <td width="20%">Promedio</td>
                    <td><?= number_format($avg_cant_login, 0) ?> login por nivel</td>
                </tr>
                
                <?php $this->load->view('estadisticas/resumen_filtros_v'); ?>
                
            </tbody>
        </table>
    </div>
      
    <div class="col col-md-3">
        <?php if ( count($campos_filtros) > 0 ) { ?>
            <?php $this->load->view('estadisticas/form_filtros_v'); ?>
        <?php } ?>
    </div>
</div>