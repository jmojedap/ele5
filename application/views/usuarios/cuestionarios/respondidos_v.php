<?php
    $res_total['cant_preguntas'] = 0;
    $res_total['correctas'] = 0;
    $res_total['cant_incorrectas'] = 0;
    $res_total['pct'] = 0;
    $res_total['cl_barra'] = 'bg-primary';

    $arr_resultados = array();

    foreach ( $cuestionarios_resp->result() as $row_cuestionario )
    {
        $condicion = "usuario_id = {$row->id}";
        $res_cuestionario = $this->App_model->res_cuestionario($row_cuestionario->cuestionario_id, $condicion);
        $arr_resultados[$row_cuestionario->cuestionario_id] = $res_cuestionario;

        $res_total['num_preguntas'] += $res_cuestionario['num_preguntas'];
        $res_total['correctas'] += $res_cuestionario['correctas'];
        $res_total['incorrectas'] += $res_cuestionario['incorrectas'];
    }

    $res_total['pct'] = $this->Pcrn->int_percent($res_total['correctas'], $res_total['num_preguntas']);
    $res_total['cl_barra'] = $this->App_model->bs_clase_pct($res_total['pct']);

    //Clases columnas
        $cl_col['pct'] = 'd-none d-sm-table-cell d-lg-table-cell';
        $cl_col['num_preguntas'] = 'd-none d-sm-table-cell d-lg-table-cell';
        $cl_col['correctas'] = 'd-none d-sm-table-cell d-lg-table-cell';
        $cl_col['incorrectas'] = 'd-none d-sm-table-cell d-lg-table-cell';
        $cl_col['respondido'] = 'd-none d-sm-table-cell d-lg-table-cell';
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var area_id = 0;

    $(document).ready(function(){
        $('.btn_area').click(function(){
            area_id = $(this).data('area_id');
            $('.btn_area').removeClass('active');
            
            $(this).addClass('active');
            filtrar_areas();
        });

        $('#todas_areas').click(function(){
            area_id = 0;
            $('.fila_cuestionario').show();
        });
    });

// Functions
//-----------------------------------------------------------------------------
    function filtrar_areas()
    {
        console.log(area_id);
        $('.fila_cuestionario').hide();
        $('.area_' + area_id).show();
    }

</script>

<ul class="nav nav-pills nav-justified mb-2">
    <li class="nav-item">
        <a href="#" class="nav-link btn_area active" id="todas_areas">
            Todas
        </a>
    </li>
    <?php foreach ( $areas->result() as $row_area ) { ?>
        <li class="nav-item">
            <a href="#" class="nav-link btn_area" data-area_id="<?php echo $row_area->id ?>">
                <?php echo $row_area->nombre_corto ?>
            </a>
        </li>
    <?php } ?>
</ul>

<table class="table table-hover bg-white">
    <thead>
        
        <th width="100px">Área</th>
        <th>Cuestionario</th>
        <th width="250px" class="<?php echo $cl_col['pct'] ?>">%</th>
        <th width="50px" class="<?php echo $cl_col['num_preguntas'] ?>">Preguntas</th>
        <th title="correctas" class="<?php echo $cl_col['correctas'] ?>"><i class="fa fa-check text-success"/></th>
        <th title="incorrectas" class="<?php echo $cl_col['incorrectas'] ?>"><i class="fa fa-times text-danger"/></th>
        <th class="<?php echo $cl_col['respondido'] ?>">Respondido</th>
        <th width="50px">Resultado</th>
    </thead>
    <tbody>
        <tr style="background-color: #e1f5fe;">
            <td><strong>Total</strong></td>

            <td><strong>Cuestionarios acumulados</strong></td>

            <td class="<?php echo $cl_col['pct'] ?>">
                <?php echo $this->App_model->bs_progress_bar($res_total['pct'], $res_total['pct'] . '%', $res_total['cl_barra']); ?>
            </td>
            <td class="<?php echo $cl_col['num_preguntas'] ?>">
                <?php echo $res_total['num_preguntas'] ?>
            </td>
            <td class="<?php echo $cl_col['correctas'] ?>">
                <?php echo $res_total['correctas'] ?>
            </td>
            <td class="<?php echo $cl_col['incorrectas'] ?>">
                <?php echo $res_total['incorrectas'] ?>
            </td>
            
            <td class="<?php echo $cl_col['respondido'] ?>"></td>
            <td></td>
        </tr>
        <?php foreach ($cuestionarios_resp->result() as $row_cuestionario) : ?>
            <?php
                
                $resultado_c = $arr_resultados[$row_cuestionario->cuestionario_id];

                $datos_cuestionario = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);

                $rango = $this->App_model->rango_cuestionarios($resultado_c['porcentaje']/100);
                $clase_barra = $this->App_model->bs_clase_pct($resultado_c['porcentaje']);

                $link_resultados = anchor("usuarios/resultados/{$row->id}/{$row_cuestionario->uc_id}",'Detalle', 'class="btn btn-outline-primary btn-sm"');
            ?>
                <tr class="fila_cuestionario <?php echo 'area_' . $row_cuestionario->area_id ?>">
                    
                    <td>
                        <?php echo $this->App_model->etiqueta_area($row_cuestionario->area_id); ?>
                    </td>
                    <td><?= $row_cuestionario->nombre_cuestionario ?></td>
                    <td class="<?php echo $cl_col['pct'] ?>">
                        <?php echo $this->App_model->bs_progress_bar($resultado_c['porcentaje'], $resultado_c['porcentaje'] . '%', $clase_barra); ?>
                    </td>
                    <td class="<?php echo $cl_col['num_preguntas'] ?>"><?= $resultado_c['num_preguntas'] ?></td>
                    <td class="<?php echo $cl_col['correctas'] ?>"><?= $resultado_c['correctas'] ?></td>
                    <td class="<?php echo $cl_col['incorrectas'] ?>"><?= $resultado_c['incorrectas'] ?></td>
                    <td class="<?php echo $cl_col['respondido'] ?>">
                        <?= $this->Pcrn->fecha_formato($row_cuestionario->fin_respuesta, 'Y-M-d') ?>
                    </td>
                    <td><?= $link_resultados ?></td>
                </tr>
        <?php endforeach ?>
    </tbody>
</table>