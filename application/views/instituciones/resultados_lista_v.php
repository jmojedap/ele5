<?php

    $num_preguntas_divisor = $this->Pcrn->no_cero($row_cuestionario->num_preguntas);
    $i = 0;
    
    $total_correctas = 0;

?>

<?= $this->load->view('instituciones/submenu_cuestionarios_v'); ?>

Cuestionario: <?= $cuestionarios_grupos->num_rows() ?>

<div class="seccion group">
    <div class="col col_box span_1_of_3">
        <div class="info_container_body">
            <?php $this->load->view($menu_sub) ?>
        </div>
    </div>
    
    <div class="col span_2_of_3">
        <div class="info_container_body">
            <table class="tablesorter" cellspacing="0">
                <thead>
                    <tr>
                        <th>Posición</th>
                        <th>Estudiante</th>
                        <th>Correctas</th>
                        <th>Incorrectas</th>
                        <th>Porcentaje</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista->result() as $row_estudiante) : ?>
                        <?php
                        //Variables
                        $i = $i + 1;
                        $total_correctas += $row_estudiante->correctas;
                        $uc_id = $this->Pcrn->campo('usuario_cuestionario', "usuario_id = {$row_estudiante->usuario_id} AND cuestionario_id = {$row_cuestionario->id}", 'id');
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $this->App_model->nombre_usuario($row_estudiante->usuario_id, 3) ?></td>
                            <td><?= $row_estudiante->correctas ?></td>
                            <td><?= $row_cuestionario->num_preguntas - $row_estudiante->correctas ?></td>
                            <td><?= number_format(100 * $row_estudiante->correctas / $num_preguntas_divisor, 0) . "%" ?></td>
                            <td><?= anchor("usuarios/resultados/{$row_estudiante->usuario_id}/{$uc_id}", "Ver") ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>

                <tfoot>
                    <tr class="total">
                        <td>Total</td>
                        <td>Promedio de <?= $lista->num_rows() ?> estudiantes</td>
                        <td><?= number_format($total_correctas / $this->Pcrn->no_cero($lista->num_rows()), 1) ?></td>
                        <td><?= number_format($row_cuestionario->num_preguntas - ($total_correctas / $this->Pcrn->no_cero($lista->num_rows())), 1) ?></td>
                        <td><?= number_format(100 * ($total_correctas / $this->Pcrn->no_cero($lista->num_rows())) / $num_preguntas_divisor, 0) ?>%</td>
                        <td></td>
                    </tr>

                </tfoot>
            </table>
        </div>
    </div>
</div>