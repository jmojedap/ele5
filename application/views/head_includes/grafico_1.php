<?php
    //$titulo_grafico = $row->nombre_apellidos;
    
    //Básico
    $correctas = array(
        'usuario' => $res_usuario['correctas'],
        'grupo' => $res_grupo['correctas']
    );
    
    $incorrectas = array(
        'usuario' => $res_usuario['incorrectas'],
        'grupo' => $res_grupo['incorrectas']
    );
    
    //Para usuarios institucionales
    if ( $this->session->userdata('rol_id') == 0 ) {
        $correctas['institucion'] = $res_institucion['correctas'];
        $incorrectas['institucion'] = $res_institucion['incorrectas'];
    }
    
    //Para usuarios enlace
    if ( $this->session->userdata('rol_id') == 0 ) {
        $correctas['total'] = $res_total['correctas'];
        $incorrectas['total'] = $res_total['incorrectas'];
    }
?>

<script type="text/javascript">
        var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column'
            },
            title: {
                text: 'Porcentaje de respuestas correctas'
            },
            xAxis: {
                categories: ['Estudiante', 'Grupo', 'Institución', 'Total']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Porcentaje de preguntas'
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.series.name +': '+ this.y +' ('+ Math.round(this.percentage) +'%)';
                }
            },
            plotOptions: {
                column: {
                    stacking: 'percent'
                }
            },
                        colors: [
                                '#f1f1f1',
                                '#10aae4'
                        ],
                series: [{
                name: 'Incorrectas',
                data: [
                    <?= $incorrectas['usuario'] ?>,
                    <?= $incorrectas['grupo'] ?>
                ]
            }, {
                name: 'Correctas',
                data: [
                    <?= $correctas['usuario'] ?>,
                    <?= $correctas['grupo'] ?>,
                ]
            }]
        });
    });
</script>