<?php
    
    $avg_cant_login = $this->Pcrn->dividir($suma_cant_login, $instituciones->num_rows());
    $avg_porcentaje = $this->Pcrn->int_percent($avg_cant_login, $max_cant_login);
    
    //Torta
        $contador_torta = 0;
        $max_con_detalle = 10;  //Cantidad máxima de instituciones que se muestran en detalle
        $suma_con_detalle = 0;
        $cant_sin_detalle = $instituciones->num_rows() - $max_con_detalle;
    
?>

<script>
$(function () {
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Colegios con más login de usuarios'
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
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [
            <?php foreach($instituciones->result() as $row) : ?>
                <?php
                    $row_institucion_full = $this->Pcrn->registro_id('institucion', $row->institucion_id);
                    $contador_torta++;
                    $suma_con_detalle += $row_institucion->cant_login;
                ?>
                                        
                <?php if ( $contador_torta < $max_con_detalle ) { ?>
                    {
                        name: '<?= $row_institucion_full->nombre_institucion ?>',
                        y: <?= $row->cant_login ?>
                    },
                <?php } ?>
                
            <?php endforeach; ?>
                {
                    name: 'Otras instituciones (<?= $cant_sin_detalle ?>)',
                    y: <?= $suma_cant_login - $suma_con_detalle ?>
                }
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
            <thead>
                <th>Institución</th>
                <th width="40%">Cantidad login</th>
                <th>Comercial</th>
            </thead>
            <tbody>
                    <tr>
                        <td>
                            <span class="resaltar">Promedio</span>
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?= $avg_porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: <?= $avg_porcentaje ?>%">
                                    <?= number_format($avg_cant_login, 0) ?>
                                </div>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                
                <?php foreach ($instituciones->result() as $row) : ?>
                    <?php
                        $row_institucion = $this->Pcrn->registro_id('institucion', $row->institucion_id);

                        $porcentaje = $this->Pcrn->int_percent($row->cant_login, $max_cant_login);
                        
                        $clase_barra = '';
                        if ( $row->cant_login < $avg_cant_login ) { $clase_barra = 'progress-bar-warning'; }
                    ?>

                    <tr>
                        <td>
                            <?= anchor("instituciones/grupos/{$row->institucion_id}", $row_institucion->nombre_institucion, 'class="" title=""') ?>
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar <?= $clase_barra ?>" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: <?= $porcentaje ?>%">
                                    <?= $row->cant_login ?>
                                </div>
                            </div>

                        </td>
                        <td><?= $this->App_model->nombre_usuario($row_institucion->ejecutivo_id, 2) ?></td>
                    </tr>

                <?php endforeach ?>

                    <tr>
                        <td><span class="resaltar">Total</span></td>
                        <td><?= number_format($suma_cant_login) ?></td>
                        <td></td>
                    </tr>
            </tbody>

        </table>
    </div>
    <div class="col col-md-3">
        <?php if ( count($campos_filtros) > 0 ) { ?>
            <?php $this->load->view('estadisticas/form_filtros_v'); ?>
        <?php } ?>
    </div>
</div>



