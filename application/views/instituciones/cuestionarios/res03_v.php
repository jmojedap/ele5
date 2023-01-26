<?php $this->load->view('head_includes/highcharts') ?>

<?php
    $condiciones[0] = "institucion_id = {$institucion_id}";
    
    //Clase nivel todos
    $clase_nivel_todos = 'btn btn-default';
    if ( is_null($nivel) ) { $clase_nivel_todos = 'btn btn-primary'; }
    
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
            text: 'Institución <?= $row->nombre_institucion ?>'
        },
        subtitle: {
            text: 'Resultados por competencias - <?= $this->App_model->nombre_item($area_id); ?>'
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
        <?php foreach ($acumuladores->result() as $row_acumulador) : ?>
            <?php
                $prefijo = '';
                if ( strlen($row_acumulador->acumulador_2) <= 3 ) {
                    //Acumuladores numéricos, no son nombre de cuestionario
                    $prefijo = 'F';
                }
            ?>
            {
                name: '<?= $prefijo . $row_acumulador->acumulador_2 ?>',
                data: [
                    <?php foreach ($competencias->result() as $row_competencia) : ?>
                        <?php
                            $filtros['competencia_id'] = $row_competencia->competencia_id;
                            $filtros['usuario_cuestionario.institucion_id'] = $row->id;
                            $filtros['acumulador_2'] = $row_acumulador->acumulador_2;
                            $resultado = $this->Cuestionario_model->up_resultado($filtros);
                        ?>
                        <?= number_format(100 * $resultado['porcentaje'], 0) ?>,
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
        $clase_area = 'btn btn-default';
        if ( $row_area->id == $area_id ) { $clase_area = 'btn btn-primary'; }
    ?>
        <?= anchor("instituciones/cuestionarios_resumen03/{$institucion_id}/{$row_area->id}/{$nivel}", $row_area->item_corto, 'class="' . $clase_area . '"' )  ?>
    <?php endforeach ?>
</div>

<div class="mb-2 text-center">
    <?= anchor("instituciones/cuestionarios_resumen03/{$institucion_id}/{$area_id}", 'Niveles', 'class="' . $clase_nivel_todos . '" title="Todos los niveles"') ?>
    <?php foreach ($niveles as $key => $nombre_nivel) : ?>
    <?php
        $clase_nivel = 'btn btn-default';
        if ( $key == $nivel ) { $clase_nivel = 'btn btn-primary'; }
    ?>
        <?= anchor("instituciones/cuestionarios_resumen03/{$institucion_id}/{$area_id}/{$key}", $key, 'class="' . $clase_nivel . '"' )  ?>
    <?php endforeach ?>     
</div>

<div class="section group ">
    <div class="col col_box span_4_of_4" id="grafica">
        <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto"></div>
    </div>
</div>
