<?php $this->load->view('head_includes/highcharts') ?>

<?php
    $condiciones[0] = "institucion_id = {$institucion_id}";
    
    //Clase nivel todos
    $clase_nivel_todos = 'btn btn-default w3';
    if ( is_null($nivel) ) { $clase_nivel_todos .= ' actual'; }
    
    if ( ! is_null($nivel) ) {
        $filtros['nivel'] = $nivel;
    }
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
                text: '<?= $row->nombre_institucion ?>'
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
                name: '<?= $this->App_model->nombre_cuestionario($row_cuestionario->cuestionario_id, 2) ?>',
                data: [
                    <?php foreach ($competencias->result() as $row_competencia) : ?>
                        <?php $condicion = "institucion_id = {$institucion_id} AND area_id = {$area_id} AND competencia_id = {$row_competencia->competencia_id}"; ?>
                        <?php $resultado = $this->Cuestionario_model->resultado($row_cuestionario->cuestionario_id, $condicion); ?>
                        <?= $resultado['porcentaje'] ?>,
                    <?php endforeach ?>
                ]
            },
            <?php endforeach ?>
            ]
        });
    });
</script>

<div class="mb-2 text-center">
    <?php foreach ($areas->result() as $row_area) : ?>
    <?php
        $clase_area = 'btn btn-default w3';
        if ( $row_area->id == $area_id ) { $clase_area = 'btn btn-primary w3'; }
    ?>
        <?= anchor("instituciones/cuestionarios_resumen01/{$institucion_id}/{$row_area->id}/{$nivel}", $row_area->item_corto, 'class="' . $clase_area . '"' )  ?>
    <?php endforeach ?>
</div>

<div class="text-center mb-2">
    <div class="btn btn-default">Niveles</div>
    <?php foreach ($niveles as $key => $nombre_nivel) : ?>
    <?php
        $clase_nivel = 'btn btn-default';
        if ( $key == $nivel ) { $clase_nivel = 'btn btn-primary'; }
    ?>
        <?= anchor("instituciones/cuestionarios_resumen01/{$institucion_id}/{$area_id}/{$key}", $key, 'class="' . $clase_nivel . '"' )  ?>
    <?php endforeach ?>     
</div>

<div class="seccion group">
    <div class="col col_box span_2_of_2">
        <div class="info_container_body">
            <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto"></div>
        </div>
    </div>
</div>