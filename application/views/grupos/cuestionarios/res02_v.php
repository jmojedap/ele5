<?php
    $condiciones[0] = "grupo_id = {$grupo_id}";
    
    //$cant_acumuladores = 20;
?>

<script>
    $(document).ready(function(){
        $('#lista_estudiantes').hide();
        $('#flecha_arriba').hide();
        
        $('#ver_estudiantes').click(function(){
            $(this).toggleClass('orange');
            $(this).toggleClass('white');
            $('#lista_estudiantes').toggle();
            $('#flecha_arriba').toggle();
            $('#flecha_abajo').toggle();
        });
    });
</script>

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
                        $filtros['grupo_id'] = $row->id;
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

<?php $this->load->view('grupos/submenu_cuestionarios_v'); ?>

<div class="div2">
    <p>
        <?php foreach ($areas->result() as $row_area) : ?>
        <?php
            $clase_area = 'a2 w3';
            if ( $row_area->id == $area_id ) { $clase_area .= ' actual'; }
        ?>
            <?= anchor("grupos/cuestionarios_resumen02/{$grupo_id}/{$row_area->id}", $row_area->item_corto, 'class="' . $clase_area . '"' )  ?>
        <?php endforeach ?>
    </p>
</div>

<div class="section group ">
    <div class="col col_box span_4_of_4" id="grafica">
        <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto"></div>
    </div>
</div>

<div class="button white" id="ver_estudiantes">
    Ver por estudiantes
    <i class="fa fa-caret-down" id="flecha_abajo"></i>
    <i class="fa fa-caret-up" id="flecha_arriba"></i>
</div>


<div class="" id="lista_estudiantes">
    <hr/>
    <h3>Ver desempeño por estudiante</h3>
    <table class="tablesorter">
        <thead>
            <th width="70px"></th>
            <th>Estudiante</th>
        </thead>
        <tbody>
            <?php foreach ($estudiantes->result() as $row_usuario) : ?>
                <tr>
                    <td>
                        <?= anchor("usuarios/cuestionarios_resumen02/{$row_usuario->id}", '<i class="fa fa-bar-chart-o"></i> Ver', 'class="a2" target="_blank"') ?>
                    </td>
                    <td>
                        <?= $row_usuario->apellidos . ' ' . $row_usuario->nombre ?>
                    </td>
                </tr>

            <?php endforeach ?>
        </tbody>
    </table>
</div>
