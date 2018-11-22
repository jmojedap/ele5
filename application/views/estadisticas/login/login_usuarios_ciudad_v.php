<?php
    //Porcentajes de usuarios por ciudad
        $arr_porcentajes = array();
        foreach ( $ciudades_login->result() as $row_ciudad ) {
            $condicion_ciudad = "institucion_id IN (SELECT id FROM institucion WHERE lugar_id = {$row_ciudad->lugar_id})";
            $cant_usuarios = $this->Pcrn->num_registros('usuario', $condicion_ciudad);
            $arr_porcentajes[$row_ciudad->lugar_id] = $this->Pcrn->int_percent($cant_usuarios, $total_usuarios);
        }
        
    //Valores
        $avg_login = $this->Pcrn->dividir($total_login, $total_usuarios);
        
    //Cálculos para ciudades restantes
        $login_otras = $total_login;            //Valor inicial, para después restarle
        $pct_usuarios_otras = 100;                       //Valor inicial, para después restarle
?>

<script>
    $(function () {
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '% usuarios registrados | % login'
            },
            subtitle: {
                text: 'Comprara por ciudad el porcentaje de usuarios registrados con el porcentaje eventos login en la plataforma.'
            },
            xAxis: {
                categories: [
                    <?php foreach($ciudades_login->result() as $row_ciudad) : ?>
                        '<?= $this->App_model->nombre_lugar($row_ciudad->lugar_id) ?>',
                    <?php endforeach; ?>
                    'Otras'
                ]
            },
            yAxis: [{
                min: 0,
                title: {
                    text: '% Usuarios'
                }
            }, {
                title: {
                    text: '% Login'
                },
                opposite: true
            }],
            legend: {
                shadow: false
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    grouping: false,
                    shadow: false,
                    borderWidth: 0
                }
            },
            series: [{
                name: '% Usuarios',
                color: 'rgba(124,181,236,1)',
                data: [
                    <?php foreach($ciudades_login->result() as $row_ciudad) : ?>
                        <?php
                            $pct_usuarios_otras -= $arr_porcentajes[$row_ciudad->lugar_id];
                        ?>
                        <?= $arr_porcentajes[$row_ciudad->lugar_id] ?>,
                        
                    <?php endforeach; ?>
                    <?= $pct_usuarios_otras ?>  //Otras ciudades
                ],
                pointPadding: 0.3
            }, {
                name: '% Login',
                color: 'rgba(137,203,78,.9)',
                data: [
                    <?php foreach($ciudades_login->result() as $row_ciudad) : ?>
                        <?php
                            $porcentaje = $this->Pcrn->int_percent($row_ciudad->cant_eventos, $total_login);
                            $login_otras -= $row_ciudad->cant_eventos;
                        ?>
                    <?= $porcentaje ?>,
                    <?php endforeach; ?>
                        
                    <?= $this->Pcrn->int_percent($login_otras, $total_login) ?> //Otras ciudades (<?= $login_otras ?>)
                ],
                pointPadding: 0.4
            }]
        });
    });
</script>

<?php $this->load->view($vista_submenu); ?>

<div class="row">
    <div class="col col-md-9">
        
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Datos
            </div>
            <div class="panel-body">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
        
        <table class="table table-default bg-blanco">
            <tbody>
                <tr>
                    <td width="20%">Total usuarios</td>
                    <td><?= number_format($total_usuarios,0) ?></td>
                </tr>
                <tr>
                    <td width="20%">Total login</td>
                    <td><?= number_format($total_login,0) ?></td>
                </tr>
                <tr>
                    <td width="20%">Promedio login</td>
                    <td><?= number_format($avg_login, 2); ?> login/usuario</td>
                </tr>
                
                <?php $this->load->view('estadisticas/resumen_filtros_v'); ?>
            </tbody>
        </table>
    </div>
      
    <div class="col col-md-3">
        <?php if ( count($campos_filtros) > 0 ) { ?>
            <?php $this->load->view('estadisticas/form_filtros_v'); ?>
        <?php } ?>
    </div>  
</div>