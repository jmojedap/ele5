<?php
    
    //Totales
        $suma_correctas = 0;
        $suma_incorrectas = 0;
        $porcentaje_correctas = 0;
    
    //Filtros detalle
        $filtros_det = $filtros;
        $filtros_det['tp'] = 13;    //Tipo de evento
?>

<script>
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Respuesta Evidencias por Nivel'
        },
        xAxis: {
            categories: [
                <?php foreach($niveles->result() as $row_nivel) : ?>
                    '<?= $row_nivel->item_largo ?>',
                <?php endforeach; ?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Respuestas'
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
            series: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
        },
        series: [{
            name: 'Incorrectas',
            data: [
                <?php foreach($niveles->result() as $row_nivel) : ?>
                    <?php
                        $filtros_det['n'] = $row_nivel->id_interno;
                        $filtros_det['est'] = 0;
                        $cant_eventos = $this->Estadistica_model->cant_eventos($filtros_det);
                        $suma_incorrectas += $cant_eventos;
                    ?>
                    <?= $cant_eventos ?>,
                <?php endforeach; ?>
            ],
            color: '#d9534f'
        }, {
            name: 'Correctas',
            data: [
                <?php foreach($niveles->result() as $row_nivel) : ?>
                    <?php
                        $filtros_det['n'] = $row_nivel->id_interno;
                        $filtros_det['est'] = 1;
                        $cant_eventos = $this->Estadistica_model->cant_eventos($filtros_det);
                        $suma_correctas += $cant_eventos;
                    ?>
                    <?= $cant_eventos ?>,
                <?php endforeach; ?>
            ],
            color: '#7cb5ec'
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
                <tr class="info">
                    <td width="20%">Total correctas</td>
                    <td>
                        <?= $suma_correctas ?> 
                        (<?= $this->Pcrn->int_percent($suma_correctas, $suma_correctas + $suma_incorrectas); ?>%)
                    </td>
                </tr>
                <tr class="danger">
                    <td width="20%">Total incorrectas</td>
                    <td>
                        <?= $suma_incorrectas ?>
                        (<?= $this->Pcrn->int_percent($suma_incorrectas, $suma_correctas + $suma_incorrectas); ?>%)
                    </td>
                </tr>
                <tr>
                    <td width="20%">Total</td>
                    <td><?= $suma_correctas + $suma_incorrectas ?> evidencias respondidas</td>
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