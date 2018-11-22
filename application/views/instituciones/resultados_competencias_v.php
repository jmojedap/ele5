<?php
    //$fecha_fin = $this->Pcrn->fecha_formato($row_uc->fecha_fin);
    //$tiempo_hace = $this->Pcrn->tiempo_hace($row_uc->fecha_fin);
    
    $i = 0;
    $total_estudiantes = 0;
    $total_correctas = 0;
    $total_incorrectas = 0;
    $total_preguntas = 0;
    
    $pre_link = "instituciones/resultados_competencia/{$row->id}/{$row_cuestionario->id}";
    
?>

<?= $this->load->view('instituciones/submenu_cuestionarios_v'); ?>

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
            <?php foreach ($areas->result() as $row_area): ?>

                <?php
                    //Variables

                    //Definir clase para el link
                    if ( $area_id == $row_area->area_id ) :
                        $att_link_area = 'class="a2 actual"';
                    else:
                        $att_link_area = 'class="a2"';
                    endif;
                ?>

                <?= anchor("{$pre_link}/{$row_area->area_id}", $this->App_model->nombre_item($row_area->area_id, 1), $att_link_area) ?>
            <?php endforeach ?>
        </div>
        
        <div class="info_container_body">
            <div id="container_1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        </div>
        
        <div class="info_container_body">
            <table class="tablesorter" cellspacing="0"> 
                <thead>
                    <tr>
                        <th>Competencia</th>
                        <th>Preguntas</th>
                        <th>Correctas</th>
                        <th>Incorrectas</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($competencias->result() as $row_2) : ?>

                            <?php
                                //Variables
                                $resultados_competencia = $resultados[$row_2->competencia_id];
                                $porcentaje = $resultados_competencia['porcentaje'] . "%";
                                $total_estudiantes += $resultados_competencia['cant_usuarios'];
                                $total_correctas += $resultados_competencia['correctas'] * $resultados_competencia['cant_usuarios'];
                                $total_incorrectas += $resultados_competencia['incorrectas'] * $resultados_competencia['cant_usuarios'];
                                $total_preguntas += $resultados_competencia['num_preguntas'];

                            ?>

                            <tr>
                                <td><?= $this->App_model->nombre_item($row_2->competencia_id, 1) ?></td>
                                <td><?= $resultados_competencia['num_preguntas'] ?></td>
                                <td><?= number_format($resultados_competencia['correctas'], 1) ?></td>
                                <td><?= number_format($resultados_competencia['incorrectas'], 1) ?></td>
                                <td><?= $porcentaje ?></td>
                            </tr>

                            <?php $i = $i + 1 ?>
                        <?php endforeach; //Recorriendo áreas ?>
                </tbody>

                <tfoot>
                        <tr class="total">
                            <td>Total</td>
                            <td><?= $total_preguntas ?></td>
                            <td><?= number_format($total_correctas / $this->Pcrn->no_cero($total_estudiantes), 1) ?></td>
                            <td><?= number_format($total_incorrectas / $this->Pcrn->no_cero($total_estudiantes), 1) ?></td>
                            <td><span class="resaltar"><?= number_format(100*($total_correctas)/$this->Pcrn->no_cero($total_correctas + $total_incorrectas), 0) . "%" ?></span></td>
                        </tr>

                    </tfoot>
            </table>
        </div>
            
        
    </div>
</div>