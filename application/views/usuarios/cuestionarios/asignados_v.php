<?php
    //Roles a los que se les oculta los links
    $roles['responder'] = array(2, 3, 4, 5, 8, 7);
    $roles['responder_lote'] = array(3, 4, 5, 8, 6);
    $roles['resultados'] = array(7);
?>

<table class="table table-hover bg-white">
    <thead>
        <th width="50px"></th>
        <th>Cuestionario</th>
        <th width="70px">Preguntas</th>
        <th title="tiempo para resolverlo (min)" width="70px">Tiempo</th>
        <th>Responder entre</th>
        <th>Faltan</th>
    </thead>
    <tbody>
        <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
        <?php
            $condicion = "usuario_id = {$usuario_id}";
            $resultado_c = $this->App_model->res_cuestionario($row_cuestionario->id, $condicion);
            $datos_cuestionario = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);

            $link_responder = '';
            $link_responder_lote = "";
            $link_resultados = "";

            if ( $row_cuestionario->usuario_id == $this->session->userdata('usuario_id') )
            {
                $link_responder = anchor("cuestionarios/preliminar/{$row_cuestionario->uc_id}",'Responder', 'class="btn btn-default"');
            }

            if ( ! in_array($this->session->userdata('rol_id'), $roles['responder_lote'] ) ){
                $link_responder_lote = anchor("cuestionarios/resolver_lote/{$row_cuestionario->uc_id}",'Lote', 'class="btn btn-primary" title="Cargar respuestas en lote"');
            }
            
            if ( $row_cuestionario->fecha_inicio > date('Y-m-d H:i:s') ) { $link_responder = ''; }

        ?>
        <tr>
            <td>
                <?= $link_responder ?>
            </td>
            <td>
                <?= $row_cuestionario->nombre_cuestionario ?>
            </td>
            <td><?= $datos_cuestionario->num_preguntas ?></td>
            <td><?= $row_cuestionario->tiempo_minutos ?></td>
            <td title="<?= $this->Pcrn->fecha_formato($row_cuestionario->fecha_fin, 'Y-M-d h:i a') ?>">
                <?= $this->Pcrn->fecha_formato($row_cuestionario->fecha_inicio, 'd M') ?>
                <span class="suave"> al </span>
                <?= $this->Pcrn->fecha_formato($row_cuestionario->fecha_fin, 'd M') ?>
            </td>
            <td>
                <?= $this->Pcrn->tiempo_hace($row_cuestionario->fecha_fin) ?>
            </td>

        </tr>
        <?php endforeach ?>
    </tbody>
</table>