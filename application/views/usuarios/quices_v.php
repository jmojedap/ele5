<?php
    $folder = RUTA_UPLOADS . 'quices/';

    $icono_resultado = array(
        '<i class="fa fa-times text-danger"></i>',
        '<i class="fa fa-check text-success"></i>'
    );
    
    $estado_defecto = array(
        'resultado' => NULL,
        'editado' => NULL,
        'cant_intentos' => NULL
    );
    
    $sum_intentos = 0;
    $sum_correctos = 0;
    $cant_respondidos = 0;
    
    foreach ($quices as $quiz)
    {
        $estado_quiz = $estado_defecto;

        if ( array_key_exists($quiz['id'], $arr_estado_quiz) )
        {
            $estado_quiz = $this->Usuario_model->estado_quiz($row->id, $quiz['id']);
        }
        
        $arr_estados[$quiz['id']] = $estado_quiz;
        $sum_intentos += $estado_quiz['cant_intentos'];
        $sum_correctos += $estado_quiz['resultado'];
        if ( $estado_quiz['cant_intentos'] > 0 ) { $cant_respondidos++; }
    }
    
    $avg_intentos = $this->Pcrn->dividir($sum_intentos, $cant_respondidos);
    $pct_respondidos = $this->Pcrn->int_percent($cant_respondidos, count($quices));
    $pct_correctos = $this->Pcrn->int_percent($sum_correctos, $cant_respondidos);
    $clase_barra = $this->App_model->bs_clase_pct($pct_correctos);
    
?>

<div class="row">
    <div class="col-md-3">
        <ul class="list-group">
            <?php foreach ($flipbooks->result() as $row_flipbook) : ?>
                <?php
                    $clase = ( $flipbook_id == $row_flipbook->flipbook_id ) ? 'active' : '' ;
                ?>
                <a class="list-group-item list-group-item-action <?= $clase ?>" title="<?= $row_flipbook->nombre_flipbook ?>" href="<?php echo base_url("usuarios/quices/{$row->id}/{$row_flipbook->flipbook_id}") ?>">
                    <?php echo $this->Item_model->nombre_id($row_flipbook->area_id) ?>
                </a>
            <?php endforeach ?>
        </ul>
    </div>
    
    <div class="col-md-9">
        <table class="table table-hover bg-white">
            <thead>
                <th>Tema</th>
                <th>Estado</th>
                <th>Intentos</th>
                <th>Hace</th>
            </thead>
            <tbody>
                <tr>
                    <td width="40%">
                        Respondidos
                        <?= $this->App_model->bs_progress_bar($pct_respondidos, $pct_respondidos . '%'); ?>
                    </td>
                    <td>
                        Correctos
                        <?= $this->App_model->bs_progress_bar($pct_correctos, $pct_correctos . '%', $clase_barra); ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($avg_intentos, 1) ?>
                    </td>
                    <td></td>
                </tr>
                <?php foreach ($quices as $quiz) : ?>
                    <?php
                        //Valores por defecto
                    
                        $estado_quiz = $arr_estados[$quiz['id']];
                        
                        $fecha = $this->Pcrn->si_nulo($estado_quiz['editado'], '-', $this->Pcrn->fecha_formato($estado_quiz['editado'], 'Y-M-d H:i'));
                        $tiempo_hace = $this->Pcrn->si_nulo($estado_quiz['editado'], '-', $this->Pcrn->tiempo_hace($estado_quiz['editado']));
                        $resultado = $this->Pcrn->si_nulo($estado_quiz['resultado'], 'Sin abrir', $icono_resultado[$estado_quiz['resultado']]);
                        
                        $clase_fila = '';
                        
                        switch ($estado_quiz['resultado']) {
                            case '':
                                $clase_fila = '';
                                break;
                            case 0:
                                $clase_fila = 'table-danger';
                                break;
                            case 1:
                                $clase_fila = 'table-success';
                                break;
                        }
                    ?>
                    <tr>
                        <td>
                            <?= anchor("quices/iniciar/{$quiz['id']}", $quiz['nombre_tema'], 'target="_blank"') ?>
                        </td>
                        <td class="text-center"><?= $resultado ?></td>
                        <td class="<?= $clase_fila ?> text-center"><?= $estado_quiz['cant_intentos'] ?></td>
                        <td>
                            <span title="<?= $fecha ?>">
                                <?= $tiempo_hace ?>
                            </span>
                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>