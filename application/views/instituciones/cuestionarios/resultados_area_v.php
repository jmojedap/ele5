<?php
    /*$fecha_fin = $this->Pcrn->fecha_formato($row_uc->fecha_fin);
    $tiempo_hace = $this->Pcrn->tiempo_hace($row_uc->fecha_fin);*/
    $i = 0;
    $total_estudiantes = 0;
    $total_correctas = 0;
    $total_incorrectas = 0;
    $total_preguntas = 0;
?>

<?php $this->load->view('instituciones/cuestionarios/submenu_cuestionarios_v'); ?>

<div class="row">
    <div class="col-md-4">
        <p>
            <span class="suave">Cuestionario: </span> 
            <span class="resaltar"><?= $row_cuestionario->cuestionario_n1 ?></span> |
            <span class="suave">Preguntas: </span> 
            <span class="resaltar"><?= $row_cuestionario->num_preguntas ?></span> |
        </p>
        <div>
            <?php echo $this->load->view($menu_sub) ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
              <div id="container_1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
        
        <br/>
        
        <div>
            <table class="table table-condensed bg-blanco" cellspacing="0">
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Correctas</th>
                        <th>Incorrectas</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($areas->result() as $row_2) : ?>

                        <?php
                        //Variables
                        $resultados_area = $resultados[$row_2->area_id];
                        $porcentaje = $resultados_area['porcentaje'] . "%";
                        $total_estudiantes += $resultados_area['cant_usuarios'];
                        $total_correctas += $resultados_area['correctas'];
                        $total_incorrectas += $resultados_area['incorrectas'];
                        $total_preguntas += $resultados_area['num_preguntas'];
                        ?>

                        <tr>
                            <td><?= $this->App_model->nombre_item($row_2->area_id, 1) ?></td>
                            <td><?= number_format($resultados_area['correctas'], 1) ?></td>
                            <td><?= number_format($resultados_area['incorrectas'], 1) ?></td>
                            <td><?= $porcentaje ?></td>
                        </tr>

                        <?php $i = $i + 1 ?>
                    <?php endforeach; //Recorriendo áreas ?>

                </tbody>
                <tfoot>
                    <tr class="total">
                        <?php
                            $total_correctas = number_format($total_correctas, 1);
                            $total_incorrectas = number_format($total_incorrectas, 1);
                            $total_porcentaje = number_format(100 * $total_correctas / ( $total_correctas + $total_incorrectas), 1);
                        ?>
                        <td>Total</td>
                        <td><?= $total_correctas ?></td>
                        <td><?= $total_incorrectas ?></td>
                        <td><span class="resaltar"><?= $total_porcentaje . "%" ?></span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="seccion group">
    <div class="col col_box span_1_of_3">
        <div class="info_container_body">      
            <p>
                <span class="suave">Cuestionario: </span> 
                <span class="resaltar"><?= $row_cuestionario->cuestionario_n1 ?></span> |
                <span class="suave">Preguntas: </span> 
                <span class="resaltar"><?= $row_cuestionario->num_preguntas ?></span> |
            </p>
            <div>
                <?php $this->load->view($menu_sub) ?>
            </div>
        </div>
    </div>
    <div class="col col_box span_2_of_3">
        <div class="info_container_body">
            
        </div>
    </div>
</div>