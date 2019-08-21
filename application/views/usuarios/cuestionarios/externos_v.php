<?php
    //Clases rango del resultado
    $clases_rango = array(
        0 => '',
        1 => 'rango_bajo',
        2 => 'rango_medio_bajo',
        3 => 'rango_medio_alto',
        4 => 'rango_alto'
    );
?>        

<p>
    Cuestionarios creados y asignados por profesores de la Institución
    <?php echo $this->Pcrn->campo_id('institucion', $this->session->userdata('institucion_id'), 'nombre_institucion') ?>
</p>

<table class="table table-hover bs-caja-no-padding">
    <thead>
        <th>Cuestionario</th>
        <th>Respondido</th>
        <th width="20px">Preguntas</th>
        <th title="correctas"><i class="fa fa-check"/></th>
        <th title="incorrectas"><i class="fa fa-times"/></th>
        <th class="centrado">%</th>
        <th width="50px">Detalle</th>
    </thead>
    <tbody>
        <?php foreach ($externos->result() as $row_cuestionario) : ?>
            <?php
                $condicion = "usuario_id = {$row->id}";
                $resultado_c = $this->App_model->res_cuestionario($row_cuestionario->cuestionario_id, $condicion);

                $datos_cuestionario = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);
                $porcentaje = 100 * $row_cuestionario->correctas / $this->Pcrn->no_cero($datos_cuestionario->num_preguntas);
                $porcentaje = number_format($porcentaje, 1);

                $rango = $this->App_model->rango_cuestionarios($resultado_c['porcentaje']/100);

                $link_resultados = anchor("usuarios/resultados/{$row->id}/{$row_cuestionario->uc_id}",'Resultado', 'class="a2"');

            ?>
                <tr>
                    <td><?= $row_cuestionario->nombre_cuestionario ?></td>
                    <td><?= $this->Pcrn->fecha_formato($row_cuestionario->fin_respuesta, 'Y-M-d') ?></td>
                    <td width="50px"><?= $resultado_c['num_preguntas'] ?></td>
                    <td><?= $resultado_c['correctas'] ?></td>
                    <td><?= $resultado_c['incorrectas'] ?></td>
                    <td class="centrado">
                            
                        <div class="<?= $clases_rango[$rango] ?>">
                            <?= $resultado_c['porcentaje'] ?>
                        </div>
                    </td>
                    
                    <td>
                        <?= $link_resultados ?>
                    </td>
                </tr>
        <?php endforeach ?>
    </tbody>
</table>