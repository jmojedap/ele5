<?php
$fecha_editado = $this->Pcrn->fecha_formato($row_uc->editado);
$tiempo_hace = $this->Pcrn->tiempo_hace($row_uc->editado);

$i = 0;
$total_estudiantes = 0;
$total_correctas = 0;
$total_incorrectas = 0;
?>

<div class="row">
    <div class="col col-md-6">
        <div class="panel panel-default">  
            <div class="panel-body">
                <div id="container_1" style="min-width: 100px; height: 400px; margin: 0 auto"></div>
            </div>
            
        </div>
    </div>

    <div class="col col-md-6">
        <div class="bs-caja-no-padding">  
            <table class="table" cellspacing="0"> 
                <thead>
                    <tr>
                        <th>Área</th>
                        <th><i class="fa fa-question"></i></th>
                        <th><i class="fa fa-check"></i></th>
                        <th><i class="fa fa-times"></i></th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($areas->result() as $row_2) : ?>

                        <?php
                        //Variables
                        $resultados_area = $resultados[$row_2->area_id];
                        $porcentaje = $resultados_area['porcentaje'] . "%";
                        $total_estudiantes += $resultados_area['num_usuarios'];
                        $total_correctas += $resultados_area['correctas'] * $resultados_area['num_usuarios'];
                        $total_incorrectas += $resultados_area['incorrectas'] * $resultados_area['num_usuarios'];
                        ?>

                        <tr>
                            <td><?= $this->App_model->nombre_item($row_2->area_id, 1) ?></td>
                            <td><?= $resultados_area['num_preguntas'] ?></td>
                            <td class="success"><?= $resultados_area['correctas'] ?></td>
                            <td class="danger"><?= $resultados_area['incorrectas'] ?></td>
                            <td><?= $porcentaje ?></td>
                        </tr>

                        <?php $i = $i + 1 ?>
                    <?php endforeach; //Recorriendo áreas ?>
                </tbody>

                <tfoot>
                    <tr class="">
                        <td>Total</td>
                        <td></td>
                        <td class="success"><?= number_format($total_correctas, 0) ?></td>
                        <td class="danger"><?= number_format($total_incorrectas, 0) ?></td>
                        <td><span class="resaltar"><?= number_format(100 * ($total_correctas) / $this->Pcrn->no_cero($total_correctas + $total_incorrectas), 0) . "%" ?></span></td>
                    </tr>

                </tfoot>
            </table>
        </div>
    </div>
</div>


