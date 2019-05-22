<script type="text/javascript">
            var chart;
        $(document).ready(function() {

            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container_1',
                    type: 'column'
                },
                title: {
                    text: '<?php echo $titulo_grafico ?>'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($componentes->result() as $row_componente) : ?>
                                '<?php echo $this->Item_model->nombre_id($row_componente->componente_id) ?>',
                        <?php endforeach ?>
                    ]
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
                        <?php foreach ($correctas as $key => $value) : ?>
                                <?= $num_preguntas_componente[$key] - $value ?>,
                        <?php endforeach ?>
                    ]
                }, {
                    name: 'Correctas',
                    data: [
                        <?php foreach ($correctas as $value) : ?>
                            <?= $value ?>,
                        <?php endforeach ?>
                    ]
                }]
            });                
            
        });
</script>