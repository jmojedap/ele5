<?php
    $sizes = array(
        'carta' => 'Letter',
        'carta_datos' => 'Letter',
        'medio_oficio' => '216mm 178mm',
        'medio_oficio_datos' => '216mm 178mm',
    );
?>

<style>
    @page { sheet-size: <?php echo $sizes[$formato] ?>; }

    table{
        border-collapse: collapse;
    }

    table.estructura{
        width: 95%;
        margin: 0 auto;
    }

    table.datos{
        width: 100%
    }

    table.datos td {
        border: 1px solid #FFF;
        padding: 2px;
    }

    div.contenedor{
        width: 800px;
        height: 1100px;
        padding: 50px 40px 30px 30px;
    }
</style>

<div class="contenedor">
    <table class="estructura">
        <tbody>
            <tr>
                <td width="450px">
                    <table class="datos">
                        <tbody>
                            <tr>
                                <td>
                                    <?php echo $row_cuestionario->nombre_cuestionario ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>
                                        <?php echo $row_uc->apellidos ?> <?php echo $row_uc->nombre ?>
                                    </b>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo $row_grupo->nombre_grupo ?> &middot;
                                    <?php echo $nombre_institucion ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo date('Y-M-d') ?> | 
                                    <?php echo $row_uc->uc_id ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="text-align: right;">
                    <img alt="Embedded Image" src="data:image/png;base64,<?php echo base64_encode($qr_code->writeString()); ?>" width="95px" style="float: right;"/>
                </td>
            </tr>
        </tbody>
    </table>
</div>
