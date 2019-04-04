<script>
    $(document).ready(function(){
        $('#field-cuestionario_id').change(function(){
            var clase_cuestionario = '.cuestionario_' + $(this).val();
            
            if ( $(this).val().length )
            {
                $('.fila_resultado').hide();
                $(clase_cuestionario).show();
            } else {
                $('.fila_resultado').show();
            }
        });
    });
</script>

<?php
    $arr_cuestionarios = array();
    $cuestionario_id = 0;

    foreach ($importados->result() as $row_uc)
    {
        if ( $cuestionario_id != $row_uc->cuestionario_id )
        {
            $arr_cuestionarios[] = array(
                'cuestionario_id' => $row_uc->cuestionario_id,
                'nombre_cuestionario' => $row_uc->nombre_cuestionario
            );
        }

        //Iguala para siguiente ciclo
        $cuestionario_id = $row_uc->cuestionario_id;
    }
?>

<h1>Resultados</h1>

<div class="row mb-2">
    <div class="col-md-4">
        <select name="cuestionario_id" id="field-cuestionario_id" class="form-control" title="Filtrar por cuestionario">
            <option value="">[ Todos los cuestionarios ]</option>
            <?php foreach ( $arr_cuestionarios as $cuestionario ) { ?>
                <option value="0<?php echo $cuestionario['cuestionario_id'] ?>">
                    <?php echo $cuestionario['nombre_cuestionario'] ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-8">
        
    </div>
</div>

<table class="table table-default bg-white">
    <tbody>
        <thead>
            <th>Cod Pag.</th>
            <th>Cuestionario</th>
            <th>Estudiante</th>
            <th>Correctas</th>
            <th>% Correctas</th>
        </thead>
        <?php foreach ( $importados->result() as $row_uc ) { ?>
            <?php
                $resumen = json_decode($row_uc->resumen);  
                $pct = $this->Pcrn->int_percent($resumen->total[0], $resumen->total[1]);
                $clase_pct = 'bg-' . $this->App_model->bs_clase_pct($pct);

                $clase_cuestionario = 'cuestionario_0' . $row_uc->cuestionario_id;
            ?>
            <tr class="fila_resultado <?php echo $clase_cuestionario ?>">
                <td><?php echo $row_uc->id ?></td>
                <td><?php echo $row_uc->nombre_cuestionario ?></td>
                <td>
                    <b><?php echo $row_uc->apellidos ?></b> <?php echo $row_uc->nombre ?>
                </td>
                <td class="text-right">
                    <b class="text-success"><?php echo $resumen->total[0] ?></b>/<?php echo $resumen->total[1] ?>
                </td>
                <td width="25%">
                    <div class="progress">
                        <div
                            class="progress-bar progress-bar-striped progress-bar-animated <?php echo $clase_pct ?>"
                            role="progressbar"
                            aria-valuenow="<?php echo $pct ?>"
                            aria-valuemin="0" aria-valuemax="100"
                            style="width: <?php echo $pct ?>%"><?php echo $pct . "%" ?></div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php if ( count($not_imported) > 0 ) { ?>
    <div class="card">
        <div class="card-header bg-danger text-white">
            Las páginas con estos códigos no fueron importadas:
        </div>
        <div class="card-body">
            <ul>
                <?php foreach ( $not_imported as $uc_id ) { ?>
                    <li>
                        <?php echo $uc_id ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>