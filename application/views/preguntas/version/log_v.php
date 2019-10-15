<table class="table bg-white">
    <thead>
        <th>Evento</th>
        <th>Fecha</th>
        <th>Hace</th>
        <th>Usuario</th>
        <th>ID Versión</th>
    </thead>
    <tbody>
        <?php foreach ( $eventos->result() as $row_evento ) { ?>
            <tr>
                <td>
                    <?php echo $arr_tipos['0' . $row_evento->tipo_id] ?>
                </td>
                <td>
                    <?php echo $this->Pcrn->fecha_formato($row_evento->fecha_inicio, 'Y-m-d') ?>
                </td>
                <td>
                    <?php echo $this->Pcrn->tiempo_hace($row_evento->fecha_inicio . ' ' . $row_evento->hora_inicio) ?>
                </td>
                <td>
                    <?php echo $this->App_model->nombre_usuario($row_evento->usuario_id, 'nau') ?>
                </td>
                <td><?php echo $row_evento->referente_2_id ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>