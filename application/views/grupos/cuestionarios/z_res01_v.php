<?php
    $condiciones[0] = "grupo_id = {$grupo_id}";
?>

<script>
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Resultados por competencias - <?= $this->App_model->nombre_item($area_id); ?>'
        },
        subtitle: {
            text: 'Grupo <?= $row->nivel . ' - ' . $row->grupo  ?>'
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
                text: '% porcentaje correctas'
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
            name: '<?= $this->App_model->nombre_cuestionario($row_cuestionario->cuestionario_id) ?>',
            data: [
                <?php foreach ($competencias->result() as $row_competencia) : ?>
                    <?php $condicion = "grupo_id = {$grupo_id} AND area_id = {$area_id} AND competencia_id = {$row_competencia->competencia_id}"; ?>
                    <?php
                        $resultado = $this->Cuestionario_model->resultado($row_cuestionario->cuestionario_id, $condicion);
                    ?>
                    <?= $resultado['porcentaje'] ?>,
                <?php endforeach ?>
            ]
        },
        <?php endforeach ?>
        ]
    });
});
</script>

<?= $this->load->view('grupos/cuestionarios/submenu_v'); ?>

<div class="section gruop">
    <p>
        <?php foreach ($areas->result() as $row_area) : ?>
        <?php
            $clase_area = 'a2 w3';
            if ( $row_area->id == $area_id ) { $clase_area .= ' actual'; }
        ?>
            <?= anchor("grupos/cuestionarios_resumen01/{$grupo_id}/{$row_area->id}", $row_area->item_corto, 'class="' . $clase_area . '"' )  ?>
        <?php endforeach ?>
    </p>
</div>

<div class="seccion group">
    <div class="col col_box span_2_of_2">
        <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto"></div>
    </div>
</div>