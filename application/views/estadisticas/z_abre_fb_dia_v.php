<?php

    $att_form = array(
        'class' => 'form1'
    );

    //Opciones de dropdowns
    $opciones_institucion = $this->App_model->opciones_institucion();
    //$opciones_nivel = $this->App_model->opciones_ref('nivel IS NOT NULL', 'nivel');

    $att_submit = array(
        'class' =>  'button white',
        'value' =>  'Filtrar'
    );

    $sum_cant_flipbooks = 0;
    
    //Variables gráfico
        $primera_fila = $serie->row();
        $point_start = substr($primera_fila->fecha_evento_f, 0, 4);
        $point_start .= ', ' . (substr($primera_fila->fecha_evento_f, 5, 2)-1);
        $point_start .= ', ' . substr($primera_fila->fecha_evento_f, 8, 2);
    
?>

<script>
    $(function () {
        $('#container').highcharts({
            chart: {
                zoomType: 'x',
                spacingRight: 20
            },
            title: {
                text: 'Lectura de flipbooks'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Cantidad de flipbooks abiertos por día' :
                    'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime',
                maxZoom: 14 * 24 * 3600000, // fourteen days
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    text: 'Usuarios'
                },
                min: 0
            },
            tooltip: {
                shared: true
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
    
            series: [{
                type: 'area',
                name: 'Usuarios',
                color: '#428bca',
                pointInterval: 24 * 3600 * 1000,
                pointStart: Date.UTC(<?= $point_start ?>),
                data: [
                    <?php foreach ($serie->result() as $row_serie) : ?>
                        <?= $row_serie->cant_flipbooks ?>,
                        
                        <?php
                            $sum_cant_flipbooks +=  $row_serie->cant_flipbooks;
                        ?>
                    <?php endforeach ?>
                ]
            }]
        });
    });
</script>

<?php

    
    
    //Promedio
        $promedio = $sum_cant_flipbooks/$this->Pcrn->no_cero($serie->num_rows());
        $promedio = number_format($promedio, 1);

?>



<div class="section group">
    <div class="col col_box span_2_of_2">
        
    
        <div class="info_container_body">
            <h2>Login de usuarios por día</h2>
            <p>
                <?php if ( $filtro['institucion_id'] > 0 ){ ?>
                    <span class="suave">Institución: </span>
                    <span class="resaltar"><?= $this->App_model->nombre_institucion($filtro['institucion_id']) ?></span> |
                <?php } ?>

                <?php if ( $filtro['nivel'] > 0 ){ ?>
                    <span class="suave">Nivel: </span>
                    <span class="resaltar"><?= $this->Item_model->nombre(3, $filtro['nivel']) ?></span> |
                <?php } ?>

            </p>
            <p>
                <span class="suave">Promedio diario: </span>
                <span class="resaltar"><?= $promedio ?> </span> flipbooks
            </p>
        </div>
        
        <div class="info_container_body">
            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    
    
    

</div>

<div class="section group">
    <div class="col col_box span_2_of_2">
        <h2 class="info_container_title">Filtros</h2>
        <div class="info_container_body">
            <?= form_open('estadisticas/abre_fb_dia', $att_form) ?>
                <div class="div1">
                    <label for="institucion_id">Institución</label>
                    <?= form_dropdown('institucion_id', $opciones_institucion, $filtro['institucion_id']); ?>
                </div>

                <div class="div1">
                    <label for="nivel">Nivel</label>
                    <?= form_dropdown('nivel', $opciones_nivel, $filtro['nivel']); ?>
                </div>

                <div class="div1">
                    <?= form_submit($att_submit) ?>
                    <?= anchor('estadisticas/abre_fb_dia', 'Todos', 'class="button white"') ?>
                </div>
            <?= form_close('') ?>
        </div>
    </div>
    
</div>