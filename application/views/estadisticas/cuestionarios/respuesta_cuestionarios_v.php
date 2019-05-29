<?php
    //Variables gráfico
        $primera_fila = $serie->row();
        $point_start = substr($primera_fila->fecha_evento_f, 0, 4);
        $point_start .= ', ' . (substr($primera_fila->fecha_evento_f, 5, 2)-1);
        $point_start .= ', ' . substr($primera_fila->fecha_evento_f, 8, 2);
        
    //Promedio
        $sum_cant_eventos = 0;
        //$promedio = 0;
?>

<script>
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Cuestionarios respondidos'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: { // don't display the dummy year
                month: '%e. %b',
                year: '%b'
            },
            title: {
                text: 'Cuestionarios'
            }
        },
        yAxis: {
            title: {
                text: 'Cuestionarios'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            pointFormat: '{point.x:%e. %b}: {point.y:.0f}'
        },

        plotOptions: {
            spline: {
                marker: {
                    enabled: false
                }
            }
        },

        series: [{
            name: 'Cuestionarios respondidos',
            data: [
            <?php foreach($serie->result() as $row_serie) : ?>
                <?php
                    $sum_cant_eventos += $row_serie->cant_eventos;
                    $mes_utc = $row_serie->mes - 1; //Enero corresponde a 0
                ?>
                [Date.UTC(<?= "{$row_serie->anio}, {$mes_utc}, {$row_serie->dia}" ?>), <?= $row_serie->cant_eventos ?>],
            <?php endforeach; ?>
            ]
        }]
    });
});
</script>

<?php
    $promedio = $this->Pcrn->dividir($sum_cant_eventos, $serie->num_rows());
?>

<?php $this->load->view($vista_submenu) ?>

<div class="row">
    <div class="col col-md-9">
        
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Datos
            </div>
            <div class="panel-body">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
        
        <table class="table table-default bg-blanco">
            <tbody>
                <tr>
                    <td width="20%">Promedio diario</td>
                    <td><?= number_format($promedio, 0, ',', '.') ?> cuestionarios/día</td>
                </tr>
                
                <?php if ( $filtros['i'] > 0 ){ ?>
                    <tr>
                        <td>Institución</td>
                        <td>
                            <?= anchor("instituciones/grupos/{$filtros['i']}", $this->App_model->nombre_institucion($filtros['i']), 'class="" title=""') ?>
                        </td>
                    </tr>
                <?php } ?>
                
                <?php if ( strlen($filtros['n']) > 0 ){ ?>
                    <tr>
                        <td>Nivel</td>
                        <td>
                            <?= $this->Item_model->nombre(3, $filtros['n']); ?>
                        </td>
                    </tr>
                <?php } ?>
                
                <?php if ( strlen($filtros['a']) > 0 ){ ?>
                    <tr>
                        <td>Área</td>
                        <td>
                            <?= $this->Item_model->nombre_id($filtros['a']); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
      
    <div class="col col-md-3">
        <?php $this->load->view('estadisticas/form_filtros_v'); ?>
    </div>  
    
</div>