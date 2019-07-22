<?php $this->load->view('head_includes/highcharts') ?>
<?php $this->load->view('head_includes/grafico_componentes') ?>

<?php
    $fecha_fin = $this->Pcrn->fecha_formato($row_uc->fecha_fin);
    $tiempo_hace = $this->Pcrn->tiempo_hace($row_uc->fecha_fin);
    
    $i = 0;
    $total_estudiantes = 0;
    $total_correctas = 0;
    $total_incorrectas = 0;
    $total_preguntas = 0;
    
    $clases_rango = array(
        0 => '',
        1 => 'rango_bajo',
        2 => 'rango_medio_bajo',
        3 => 'rango_medio_alto',
        4 => 'rango_alto'
    );
    
?>

<table class="table bg-white" cellspacing="0"> 
    <thead>
        <tr>
            <th>Componente</th>
            <th><i class="fa fa-question"></i></th>
            <th><i class="fa fa-check"></i></th>
            <th><i class="fa fa-times"></i></th>
            <th>%</th>
            <th>Resultado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($componentes->result() as $row_2) : ?>

                <?php
                    //Variables
                    $resultados_componente = $resultados[$row_2->componente_id];
                    $porcentaje = $resultados_componente['porcentaje'] . "%";
                    $total_estudiantes += $resultados_componente['num_usuarios'];
                    $total_correctas += $resultados_componente['correctas'] * $resultados_componente['num_usuarios'];
                    $total_incorrectas += $resultados_componente['incorrectas'] * $resultados_componente['num_usuarios'];
                    $total_preguntas += $resultados_componente['num_preguntas'];

                    //Rango resultados
                    $rango = $this->App_model->rango_cuestionarios($porcentaje / 100);

                    $clase_rango = '';
                    if ( $rango > 0 ){
                        $clase_rango = $clases_rango[$rango];
                    }


                ?>

                <tr>
                    <td><?php echo $this->App_model->nombre_item($row_2->componente_id, 1) ?></td>
                    <td><?php echo $resultados_componente['num_preguntas'] ?></td>
                    <td class="success"><?php echo $resultados_componente['correctas'] ?></td>
                    <td><?php echo $resultados_componente['incorrectas'] ?></td>
                    <td><?php echo $porcentaje ?></td>
                    <td>
                        <div class="<?php echo $clase_rango ?>">
                            <?php echo $this->Item_model->nombre(154, $rango) ?>
                        </div>
                    </td>
                </tr>

                <?php $i = $i + 1 ?>
            <?php endforeach; //Recorriendo áreas ?>
    </tbody>

    <tfoot>
            <tr class="total">
                <td>Total</td>
                <td><?php echo $total_preguntas ?></td>
                <td class="success"><?php echo number_format($total_correctas, 0) ?></td>
                <td><?php echo number_format($total_incorrectas, 0) ?></td>
                <td><span class="resaltar"><?php echo number_format(100*($total_correctas)/$this->Pcrn->no_cero($total_correctas + $total_incorrectas), 0) . "%" ?></span></td>
                <td colspan="1"></td>
            </tr>

        </tfoot>
</table>

<div class="panel panel-default">
    <div class="panel-body">
        <div id="container_1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
</div>