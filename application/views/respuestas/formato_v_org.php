<style>
    @page { sheet-size: Letter; }

    table{
        border-collapse: collapse;
    }

    table.estructura{
        width: 100%
    }

    table.datos{
        width: 100%
    }

    table.datos td {
        border: 1px solid #CCC;
        padding: 2px;
    }

    div.contenedor{
        background-image: url('<?php echo URL_IMG ?>respuestas/hoja_respuestas_703.png');
        height: 1100px;
        padding: 30px;
    }
</style>

<div class="contenedor">
    <table class="estructura">
        <tbody>
            <tr>
                <td width="80%">
                    <table class="datos">
                        <tbody>
                            <tr>
                                <td>Cuestionario</td>
                                <td>
                                    <?php echo $row_cuestionario->nombre_cuestionario ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Estudiante</td>
                                <td><?php echo $row_uc->apellidos ?> <?php echo $row_uc->nombre ?></td>
                            </tr>
                            <tr>
                                <td>Grupo</td>
                                <td>
                                    <?php echo $row_grupo->nombre_grupo ?> &middot;
                                    <?php echo $nombre_institucion ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha:</td>
                                <td><?php echo date('Y-M-d') ?></td>
                            </tr>
                            <tr>
                                <td>Serial</td>
                                <td><?php echo $row_uc->uc_id ?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="text-align: right;">
                    <img alt="Embedded Image" src="data:image/png;base64,<?php echo base64_encode($qr_code->writeString()); ?>" width="120px" style="float: left;"/>
                </td>
            </tr>
        </tbody>
    </table>
</div>
