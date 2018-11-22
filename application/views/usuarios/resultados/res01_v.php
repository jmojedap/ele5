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

<?= $this->load->view('usuarios/cuestionarios_submenu_v'); ?>

<div class="btn-group sep1">
    <?php foreach ($areas->result() as $row_area) : ?>
        <?php
            $clase_area = 'w3 btn btn-default';
            if ( $row_area->id == $area_id ) { $clase_area = 'w3 btn btn-primary'; }
        ?>
        <?= anchor("usuarios/cuestionarios_resumen01/{$usuario_id}/{$row_area->id}", $row_area->item_corto, 'class="' . $clase_area . '"' )  ?>
    <?php endforeach ?>
</div>

<div class="seccion group">
    <div class="col col_box span_2_of_2">
        <div class="info_container_body">
            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</div>

<div class="section group" style="display: none">
    <div class="col col_box span_3_of_3">
        <div class="info_container_body">
            <h2>Cuestionarios</h2>
            <?php $condiciones = array() ?>
            <?php $condiciones[0] = "usuario_id = {$usuario_id}"; ?>
            <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
                <?php
                    $condiciones[1] = "cuestionario_id = {$row_cuestionario->cuestionario_id}";
                ?>
                <h3><?= $row_cuestionario->nombre_cuestionario ?></h3>
                <?php foreach ($nombres_competencias as $key => $competencia_id) : ?>
                    <?php
                        $condiciones[2] = "competencia_id = {$key}";

                        //$resultado = $this->Cuestionario_model->resultado_detalle($condiciones);
                        $resultado = $this->App_model->res_cuestionario($row_cuestionario->cuestionario_id, $condiciones[0], $condiciones[2]);
                    ?>
                        <?= str_repeat('||', $resultado['porcentaje']) . " - " . $resultado['porcentaje'] ?>
                        ::>
                        <?= $competencia_id ?>
                        <br/>
                        
                <?php endforeach //Componentes ?> 
            <?php endforeach // Cuestionarios ?>
            
            
        </div>
    </div>
</div>