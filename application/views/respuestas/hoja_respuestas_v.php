<style>
    @page { sheet-size: Letter; }

    table{
        width: 70%;
        border-collapse: collapse;
    }

    td {
        border: 1px solid #AAA;
        padding: 2px;
    }

    div.contenedor{
        border: 1px solid #AAA;
        background-image: url('<?php echo URL_IMG ?>respuestas/hoja_respuestas_700.png');
        height: 1100px;
        padding: 30px;
    }
</style>

<div class="contenedor">
    <table class="" style="">
        <tbody>
            <tr>
                <td width="25%">Cuestionario</td>
                <td>
                    <?php echo $row_cuestionario->nombre_cuestionario ?>
                </td>
            </tr>
            <tr>
                <td>Estudiante</td>
                <td><?php echo $row_usuario->apellidos ?> <?php echo $row_usuario->nombre ?></td>
            </tr>
            <tr>
                <td>Fecha:</td>
                <td><?php echo date('Y-M-d') ?></td>
            </tr>
            <tr>
                <td>Serial</td>
                <td><?php echo $row_uc->id ?></td>
            </tr>
        </tbody>
    </table>
</div>
