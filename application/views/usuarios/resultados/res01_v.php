<?php $this->load->view('head_includes/highcharts') ?>

<?php
   $condiciones[0] = "usuario_id = {$usuario_id}";
?>

<script>
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Resultados por competencias - <?= $this->App_model->nombre_item($area_id); ?> '
            },
            subtitle: {
                text: '<?= $row->nombre . ' ' . $row->apellidos  ?>'
            },
            xAxis: {
                categories: [
                    <?php foreach ($competencias->result() as $row_competencia) : ?>
                        '<?= $row_competencia->nombre_competencia ?>',
                    <?php endforeach ?>
                ]
            },
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: '%'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [
            <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
            {
                name: '<?= $row_cuestionario->nombre_cuestionario ?>',
                data: [
                    <?php foreach ($competencias->result() as $row_competencia) : ?>
                        <?php  $condiciones[1] = "competencia_id = {$row_competencia->competencia_id}" ?>
                        <?php $resultado = $this->App_model->res_cuestionario($row_cuestionario->cuestionario_id, $condiciones[0], $condiciones[1]); ?>
                        <?= $resultado['porcentaje'] ?>,
                    <?php endforeach ?>
                ]
            },
            <?php endforeach ?>
            ]
        });
    });
</script>

<div class="nav nav-pills nav-justified mb-2">
    <?php foreach ($areas->result() as $row_area) : ?>
        <?php
            $clase_area = ( $row_area->id == $area_id ) ? 'active' : 'b' ;
        ?>
        <li class="nav-item">
            <a href="<?php echo base_url("usuarios/cuestionarios_resumen01/{$usuario_id}/{$row_area->id}") ?>" class="nav-link <?php echo $clase_area ?>">
                <?php echo $row_area->item_corto ?>
            </a>
        </li>
    <?php endforeach ?>
</div>

<div class="card">
    <div class="card-body">
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>