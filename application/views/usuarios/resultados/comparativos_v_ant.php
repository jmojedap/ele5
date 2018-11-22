<?php $this->load->view('head_includes/highcharts'); ?>

<?php
    //Filtros por competencia 1, 2, 3
        $filtros['usuario_cuestionario.cuestionario_id'] = $row_uc->cuestionario_id;
        $filtros['usuario_cuestionario.usuario_id'] = $row->id;
    
    //Íconos resultado competencias
        $iconos[0] = '<span class="etiqueta alerta w1"><i class="fa fa-times"></i></span>';
        $iconos[1] = '<span class="etiqueta exito w1"><i class="fa fa-check"></i></span>';

//Varoables gráfico
    
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
    if ( $this->session->userdata('rol_id') == 0 ) 
    {
        $correctas['institucion'] = $res_institucion['correctas'];
        $incorrectas['institucion'] = $res_institucion['incorrectas'];
    }
    
    //Para usuarios enlace
    if ( $this->session->userdata('rol_id') == 0 ) 
    {
        $correctas['total'] = $res_total['correctas'];
        $incorrectas['total'] = $res_total['incorrectas'];
    }
?>

<script type="text/javascript">
    $(document).ready(function(){
        
        //$('#tabla_competencias').hide();
        $('#ver_competencias').hide();
        
        $('#ver_competencias').click(function(){
           $('#tabla_competencias').toggle('fast');
        });
    });
    
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
                    <?= $correctas['grupo'] ?>
                ]
            }]
        });
    });

    
</script>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="container" style="min-width: 400px; height: 500px; margin: 0 auto"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="info_container_body">
            <table class="table table-hover bg-blanco" cellspacing="0"> 
                <thead>
                    <tr>
                        <th></th>
                        <th><i class="fa fa-user"></i></th>
                        <th><i class="fa fa-check"></i></th>
                        <th><i class="fa fa-times"></i></th>
                        <th width="25%">%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $row->nombre_apellidos ?></td>
                        <td>1</td>
                        <td><?= $res_usuario['correctas'] ?></td>
                        <td><?= $res_usuario['incorrectas'] ?></td>
                        <td>
                            <?= $this->App_model->bs_progress_bar($res_usuario['porcentaje'], $res_usuario['porcentaje'] . '%'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Grupo</td>
                        <td><?= $res_grupo['cant_usuarios_total'] ?></td>
                        <td><?= number_format($res_grupo['correctas'],1) ?></td>
                        <td><?= number_format($res_grupo['incorrectas'],1) ?></td>
                        <td>
                            <?php //number_format(,1) ?>
                            <?= $this->App_model->bs_progress_bar($res_grupo['porcentaje'], $res_grupo['porcentaje'] . '%'); ?>
                        </td>
                    </tr>
                    
                    <?php if ( $this->session->userdata('rol_id') == 555 ){ ?>
                        <tr>
                            <td>Institución</td>
                            <td><?= $res_institucion['cant_usuarios_total'] ?></td>
                            <td><?= $res_institucion['correctas'] ?></td>
                            <td><?= $res_institucion['incorrectas'] ?></td>
                            <td><?= $res_institucion['porcentaje'] ?></td>
                        </tr>    
                    <?php } ?>
                        
                    <?php if ( $this->session->userdata('rol_id') == 555 ) : ?>                
                        <tr>
                            <td>Total</td>
                            <td><?= $res_total['cant_usuarios_total'] ?></td>
                            <td><?= $res_total['correctas'] ?></td>
                            <td><?= $res_total['incorrectas'] ?></td>
                            <td><?= $res_total['porcentaje'] ?></td>
                        </tr>
                    <?php endif ?>
                        
                    
                </tbody>
            </table>
            
            <table class="table table-hover bg-blanco">
                <thead>
                    <th>Nombre tema</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                </thead>
                <tbody>
                    <?php foreach ($temas->result() as $row_tema) { ?>
                        <?php
                            $resultado_competencias[1] = 0;
                            $resultado_competencias[2] = 0;
                            $resultado_competencias[3] = 0;
                            
                            $filtros['tema_id'] = $row_tema->tema_id;
                            
                            for ($i = 1; $i <= 3; $i++) 
                            {
                                $filtros['abreviatura'] = $i;
                                $resultado_competencias[$i] = $this->Cuestionario_model->cant_correctas($filtros);
                            }
                        ?>
                        <tr>
                            <td><?= $this->App_model->nombre_tema($row_tema->tema_id) ?></td>
                            <td><?= $iconos[$resultado_competencias[1]] ?></td>
                            <td><?= $iconos[$resultado_competencias[2]] ?></td>
                            <td><?= $iconos[$resultado_competencias[3]] ?></td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
            
            <h3>Competencias</h3>
            
            <div id="tabla_competencias">
                <table class="table table-hover bg-blanco">
                    <thead>
                        <th width="40px">Cód.</th>
                        <th width="150px">Área</th>
                        <th>Competencia</th>
                    </thead>
                    <tbody>
                        <?php foreach ($competencias->result() as $row_competencia) : ?>
                            <tr>
                                <td>C<?= $row_competencia->abreviatura ?></td>
                                <td><?= $this->App_model->etiqueta_area($row_competencia->item_grupo) ?></td>
                                <td><?= $row_competencia->item ?></td>
                            </tr>

                        <?php endforeach ?>
                    </tbody>
                </table>
                <hr/>
                
            </div>
        </div>
    </div>
</div>