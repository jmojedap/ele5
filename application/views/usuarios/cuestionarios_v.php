<?php

    if ($cuestionarios_resp->num_rows() == 0)
    {
        $num_cuestionarios = 0;
    } else {
        $num_cuestionarios = $cuestionarios_resp->num_rows();
    }
    
    //Roles a los que se les oculta los links
        $roles['responder'] = array(2, 3, 4, 5, 8, 7);
        $roles['responder_lote'] = array(3, 4, 5, 8, 6);
        $roles['resultados'] = array(7);
        
    //Icono respondido
        $icono_respondido = '<i class="fa fa-check-square-o" />';
    
    //Clases rango del resultado
    $clases_rango = array(
        0 => '',
        1 => 'rango_bajo',
        2 => 'rango_medio_bajo',
        3 => 'rango_medio_alto',
        4 => 'rango_alto'
    );
    
    //Array porcentajes para gráfico
    $porcentajes = array();
    foreach ($cuestionarios_resp->result() as $row_cuestionario)
    {
        $datos_cuestionario = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);
        $porcentaje = 100 * $row_cuestionario->correctas / $this->Pcrn->no_cero($datos_cuestionario->num_preguntas);
        $porcentajes[] = number_format($porcentaje, 1);
    }
    
    //Clases pestanas
    $clase_pestana[0] = '';
    $clase_pestana[1] = '';
    $clase_pestana[2] = '';
    
    $clase_pestana[$pestana] = 'active';

?>

<?= $this->load->view('usuarios/cuestionarios_submenu_v'); ?>

<div class="">
    <div class="">
        <div class="">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="<?= $clase_pestana[0] ?>"><a href="#asignados" data-toggle="tab">Sin responder <span class="etiqueta informacion"><?= $cuestionarios->num_rows() ?></span></a></li>
                <li class="<?= $clase_pestana[1] ?>"><a href="#respondidos" data-toggle="tab">Resultados <span class="etiqueta informacion"><?= $cuestionarios_resp->num_rows() ?></span></a></li>
                <li class="<?= $clase_pestana[2] ?>"><a href="#externos" data-toggle="tab">Otros resultados <span class="etiqueta informacion"><?= $externos->num_rows() ?></span></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                
                <div class="tab-pane <?= $clase_pestana[0] ?>" id="asignados">
                    
                    <table class="table table-hover bs-caja-no-padding">
                        <br/>
                        
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
                </div>
                
                <div class="tab-pane <?= $clase_pestana[1] ?>" id="respondidos">
                    
                    <br/>
                    
                    <div class="pcrn-box-no-padding">
                        <table class="table table-hover bs-caja-no-padding">
                            <thead>
                                <th width="50px">Detalle</th>
                                <th>Cuestionario</th>
                                <th>%</th>
                                <th>Preguntas</th>
                                <th title="correctas"><i class="fa fa-check"/></th>
                                <th title="incorrectas"><i class="fa fa-times"/></th>
                                <th>Respondido</th>

                            </thead>
                            <tbody>
                                <?php foreach ($cuestionarios_resp->result() as $row_cuestionario) : ?>
                                    <?php
                                        $condicion = "usuario_id = {$row->id}";
                                        $resultado_c = $this->App_model->res_cuestionario($row_cuestionario->cuestionario_id, $condicion);

                                        $datos_cuestionario = $this->Cuestionario_model->datos_cuestionario($row_cuestionario->cuestionario_id);
                                        //$porcentaje = 100 * $row_cuestionario->correctas / $this->Pcrn->no_cero($datos_cuestionario->num_preguntas);
                                        //$porcentaje = number_format($porcentaje, 1);

                                        $rango = $this->App_model->rango_cuestionarios($resultado_c['porcentaje']/100);
                                        $clase_barra = $this->App_model->bs_clase_pct($resultado_c['porcentaje']);

                                        $link_resultados = anchor("usuarios/resultados/{$row->id}/{$row_cuestionario->uc_id}",'Resultado', 'class="btn btn-info"');

                                    ?>
                                        <tr>
                                            <td>
                                                <?= $link_resultados ?>
                                            </td>
                                            <td><?= $row_cuestionario->nombre_cuestionario ?></td>
                                            <td>
                                                <?php echo $this->App_model->bs_progress_bar($resultado_c['porcentaje'], $resultado_c['porcentaje'] . '%', $clase_barra); ?>
                                            </td>
                                            <td><?= $resultado_c['num_preguntas'] ?></td>
                                            <td><?= $resultado_c['correctas'] ?></td>
                                            <td><?= $resultado_c['incorrectas'] ?></td>
                                            <td><?= $this->Pcrn->fecha_formato($row_cuestionario->fin_respuesta, 'Y-M-d') ?></td>
                                        </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    

                </div>
                
                <div class="tab-pane <?= $clase_pestana[2] ?>" id="externos">
                    
                    <p>
                        Cuestionarios creados y asignados por profesores de la Institución
                        <?= $this->App_model->nombre_institucion($this->session->userdata('institucion_id')) ?>
                    </p>
                    
                    <table class="table table-hover bs-caja-no-padding">
                        <thead>
                            <th width="50px">Detalle</th>
                            <th>Cuestionario</th>
                            <th>Respondido</th>
                            <th>Preguntas</th>
                            <th title="correctas"><i class="fa fa-check"/></th>
                            <th title="incorrectas"><i class="fa fa-times"/></th>
                            <th class="centrado">%</th>
                            
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
                                        <td>
                                            <?= $link_resultados ?>
                                        </td>
                                        <td><?= $row_cuestionario->nombre_cuestionario ?></td>
                                        <td><?= $this->Pcrn->fecha_formato($row_cuestionario->fin_respuesta, 'Y-M-d') ?></td>
                                        <td><?= $resultado_c['num_preguntas'] ?></td>
                                        <td><?= $resultado_c['correctas'] ?></td>
                                        <td><?= $resultado_c['incorrectas'] ?></td>
                                        <td class="centrado">
                                                
                                            <div class="<?= $clases_rango[$rango] ?>">
                                                <?= $resultado_c['porcentaje'] ?>
                                            </div>
                                        </td>
                                        
                                    </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            
        </div>
    </div>
</div>
        
    


