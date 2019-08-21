<?php $this->load->view('head_includes/highcharts'); ?>

<?php
    //Filtros por competencia 1, 2, 3
        $filtros['usuario_cuestionario.cuestionario_id'] = $row_uc->cuestionario_id;
        $filtros['usuario_cuestionario.usuario_id'] = $row->id;
    
    //Íconos resultado competencias
        $iconos[0] = '<span class="etiqueta alerta w1"><i class="fa fa-times"></i></span>';
        $iconos[1] = '<span class="etiqueta exito w1"><i class="fa fa-check"></i></span>';

//Variables gráfico
    
    //Básico
    $correctas = array(
        'usuario' => $res_usuario['correctas'],
        'grupo' => $res_grupo['correctas']
    );
    
    $incorrectas = array(
        'usuario' => $res_usuario['incorrectas'],
        'grupo' => $res_grupo['incorrectas']
    );
    
    //Para usuarios institucionales
    if ( $this->session->userdata('rol_id') == 0 ) 
    {
        $correctas['institucion'] = $res_institucion['correctas'];
        $incorrectas['institucion'] = $res_institucion['incorrectas'];
    }
    
    //Para usuarios enlace
    if ( $this->session->userdata('rol_id') == 0 ) 
    {
        $correctas['total'] = $res_total['correctas'];
        $incorrectas['total'] = $res_total['incorrectas'];
    }
    
    //Clases barras
        $clase_barra = 'danger';
        if ( $res_usuario['porcentaje'] > $res_grupo['porcentaje'] ) { $clase_barra = 'success'; }
?>

<div class="row">
    <div class="col-md-6">
        <table class="table table-default bg-white" cellspacing="0"> 
            <thead>
                <tr>
                    <th><?= $row_cuestionario->nombre_cuestionario ?></th>
                    <th width="30%">%</th>
                    <th class="text-center"><i class="fa fa-check text-success"></i></th>
                    <th class="text-center"><i class="fa fa-times text-danger"></i></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $row->nombre_apellidos ?></td>
                    <td>
                        <?= $this->App_model->bs_progress_bar($res_usuario['porcentaje'], number_format($res_usuario['porcentaje'], 0) . '%', $clase_barra); ?>
                    </td>
                    <td class="text-center"><?= $res_usuario['correctas'] ?></td>
                    <td class="text-center"><?= $res_usuario['incorrectas'] ?></td>
                </tr>
                <tr>
                    <td>Grupo <?= $this->App_model->nombre_grupo($row_uc->grupo_id, 1) ?></td>
                    <td>
                        <?= $this->App_model->bs_progress_bar($res_grupo['porcentaje'], $res_grupo['porcentaje'] . '%'); ?>
                    </td>
                    <td class="text-center"><?= number_format($res_grupo['correctas'],1) ?></td>
                    <td class="text-center"><?= number_format($res_grupo['incorrectas'],1) ?></td>
                </tr>

                <?php if ( $this->session->userdata('rol_id') == 555 ){ ?>
                    <tr>
                        <td>Institución</td>
                        <td><?= $res_institucion['cant_usuarios_total'] ?></td>
                        <td><?= $res_institucion['correctas'] ?></td>
                        <td><?= $res_institucion['incorrectas'] ?></td>
                        <td><?= $res_institucion['porcentaje'] ?></td>
                    </tr>    
                <?php } ?>

                <?php if ( $this->session->userdata('rol_id') == 555 ) : ?>                
                    <tr>
                        <td>Total</td>
                        <td><?= $res_total['cant_usuarios_total'] ?></td>
                        <td><?= $res_total['correctas'] ?></td>
                        <td><?= $res_total['incorrectas'] ?></td>
                        <td><?= $res_total['porcentaje'] ?></td>
                    </tr>
                <?php endif ?>


            </tbody>
        </table>
        
        <h4>Competencias</h4>
        
        <table class="table bg-white">
            <thead>
                <th width="40px">Cód.</th>
                <th width="150px">Área</th>
                <th>Competencia</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($competencias->result() as $row_competencia) : ?>
                    <?php 
                        $filtros['abreviatura'] = $row_competencia->abreviatura;
                        $res_competencia = $this->Cuestionario_model->up_resultado($filtros);
                        $pct = $this->Pcrn->int_percent($res_competencia['porcentaje']);
                        $clase_barra = '';
                        if ( $pct < $res_usuario['porcentaje'] ) { $clase_barra = 'danger'; }
                        if ( $pct >= 90 ) { $clase_barra = 'success'; }
                    ?>
                    <tr>
                        <td width="10px">C<?= $row_competencia->abreviatura ?></td>
                        <td><?= $this->App_model->etiqueta_area($row_competencia->item_grupo) ?></td>
                        <td><?= $row_competencia->item ?></td>
                        <td width="30%">
                            <?= $this->App_model->bs_progress_bar($pct, $pct . '%', $clase_barra); ?>
                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>

        <h4>Resumen por temas / competencias</h4>
        <table class="table bg-white">
            <thead>
                <th>Nombre tema</th>
                <th>C1</th>
                <th>C2</th>
                <th>C3</th>
            </thead>
            <tbody>
                <?php foreach ($temas->result() as $row_tema) { ?>
                    <?php
                        $filtros['tema_id'] = $row_tema->tema_id;
                    ?>
                    <tr>
                        <td width="40%">
                            <?= $this->App_model->nombre_tema($row_tema->tema_id) ?>
                        </td>
                        <?php for ($i = 1; $i <= 3; $i++) { ?>
                            <?php
                                $filtros['abreviatura'] = $i;
                                $res_competencia_tema[$i] = $this->Cuestionario_model->up_resultado($filtros);
                                $pct = $this->Pcrn->int_percent($res_competencia_tema[$i]['porcentaje']);
                                $clase_barra = '';
                                if ( $pct < $res_usuario['porcentaje'] ) { $clase_barra = 'danger'; }
                                if ( $pct >= 90 ) { $clase_barra = 'success'; }
                            ?>
                            <td>
                                <?php //$pct ?>
                                <?= $this->App_model->bs_progress_bar($pct, $pct . '%', $clase_barra); ?>
                            </td>
                        <?php } ?>
                        
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <div class="col-md-6">
        <h4>Detalle preguntas por competencia</h4>
        <table class="table bg-white">
            <thead>
                <tr>
                    <th width="40px">#</th>
                    <th width="40px">Res</th>
                    <th>Tema</th>
                    <th>Competencia</th>
                </tr>
            </thead>
            <tbody>
                <?php $contador_pregunta = 0; ?>
                <?php foreach ($respuestas_cuestionario->result() as $row_pregunta): ?>
                <?php
                    $contador_pregunta++;
                
                    $icono_resultado = '<i class="fa fa-check text-success"></i>';
                    $clase_fila = 'success';
                    if ( $row_pregunta->resultado == 0 ) 
                    {
                        $icono_resultado = '<i class="fa fa-times text-danger"></i>';
                        $clase_fila = 'danger';
                    }
                ?>

                    <tr>
                        <td class="table-<?= $clase_fila ?> text-center">
                            <?= $contador_pregunta ?>
                        </td>
                        <td class="text-center">
                            <span class="label label-<?= $clase_fila ?>">
                                <?= $icono_resultado ?>
                            </span>
                        </td>
                        <td>
                            <?= $this->App_model->nombre_tema($row_pregunta->tema_id); ?>
                        </td>
                        <td class="table-<?= $clase_fila ?>">
                            <?= $this->Item_model->nombre_id($row_pregunta->competencia_id); ?>
                        </td>
                    <tr/>   
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>