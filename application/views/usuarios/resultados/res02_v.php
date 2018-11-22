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
            text: 'Resultados por competencias - <?= $this->App_model->nombre_item($area_id); ?>'
        },
        subtitle: {
            text: '<?= $row->nombre . ' ' . $row->apellidos  ?>'
        },
        xAxis: {
            categories: [
                <?php foreach ($nombres_competencias as $key => $nombre_competencia) : ?>
                    '<?= $nombre_competencia ?>',
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
        <?php for ( $i = 1; $i  <= $cant_acumuladores; $i++ ) { ?>
        {
            name: '<?= 'F' . $i ?>',
            data: [
                <?php foreach ($nombres_competencias as $key => $nombre_competencia) : ?>
                    <?php
                        $filtros['competencia_id'] = $key;
                        $filtros['usuario_pregunta.usuario_id'] = $row->id;
                        $filtros['acumulador'] = $i;
                        $resultado = $this->Cuestionario_model->up_resultado($filtros);
                    ?>
                    <?= number_format(100 * $resultado['porcentaje'], 0) ?>,
                <?php endforeach ?>
            ]
        },
        <?php } ?>
        ]
    });
});
</script>

<?= $this->load->view('usuarios/cuestionarios_submenu_v'); ?>

<div class="section gruop">
    <p>
        <?php foreach ($areas->result() as $row_area) : ?>
        <?php
            $clase_area = 'a2 w3';
            if ( $row_area->id == $area_id ) { $clase_area .= ' actual'; }
        ?>
            <?= anchor("usuarios/cuestionarios_resumen02/{$usuario_id}/{$row_area->id}", $row_area->item_corto, 'class="' . $clase_area . '"' )  ?>
        <?php endforeach ?>
    </p>
</div>

<div class="seccion group">
    <div class="col col_box span_2_of_2">
        <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto"></div>
    </div>
</div>