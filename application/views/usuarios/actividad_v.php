<?php
    $clases['todos'] = '';
    if ( is_null($tipo_evento_id) ) { $clases['todos'] = 'active'; }
    $clases[$tipo_evento_id] = 'active';
?>
<div class="row">
    <div class="col-md-3 div2">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-pills nav-stacked">
                  <li role="presentation" class="<?= $clases['todos'] ?>">
                      <?= anchor("usuarios/actividad/{$row->id}", 'Todos <span class="badge">'. $cant_eventos_total . '<span>', 'title="Todos los eventos"') ?>
                  </li>
                  
                  <?php foreach ($tipo_eventos->result() as $row_tipo_evento) : ?>
                    <?php
                        $condicion = "usuario_id = {$row->id} AND tipo_evento_id = {$row_tipo_evento->tipo_evento_id}";
                        $cant_eventos = $this->Pcrn->num_registros('sis_evento', $condicion);
                        $texto_link =  $row_tipo_evento->tipo_evento . '<span class="badge">' . $cant_eventos . '</span> ';
                    ?>
                    <li role="presentation" class="<?= $clases[$row_tipo_evento->tipo_evento_id] ?>">
                        <?= anchor("usuarios/actividad/{$row->id}/{$row_tipo_evento->tipo_evento_id}", $texto_link, 'title="Lectura de contenidos"') ?>
                    </li>
                  <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-9 div2">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php foreach ($eventos->result() as $row_evento) : ?>
                    <?php
                        switch ($row_evento->tipo_evento_id) {
                            case 1:
                                $texto_evento = ' ingresó a la Plataforma';
                                break;
                            case 2:
                                $texto_evento = ' cerró sesión';
                                break;
                            case 3:
                                $texto_evento = ' abrió el contenido ';
                                $texto_evento .= anchor("flipbooks/aperturas/{$row_evento->referente_id}", $this->App_model->nombre_flipbook($row_evento->referente_id));
                                break;
                            case 4:
                                $texto_evento = ' inició a responder el cuestionario ';
                                $texto_evento .= anchor("cuestionarios/preguntas/{$row_evento->referente_id}", $this->App_model->nombre_cuestionario($row_evento->referente_id));
                                break;
                            case 5:
                                $texto_evento = ' terminó de responder el cuestionario ';
                                $texto_evento .= anchor("cuestionarios/preguntas/{$row_evento->referente_id}", $this->App_model->nombre_cuestionario($row_evento->referente_id));
                                break;
                        }
                    ?>
                    <?= anchor("usuarios/actividad/1", $this->App_model->nombre_usuario($row_evento->usuario_id, 2), 'class="" title=""') ?>
                    <span class="suave"><?= $texto_evento ?></span>
                    <br/>
                    <span class="suave" title="<?= $this->Pcrn->fecha_formato($row_evento->fecha_evento, 'Y-M-d'); ?>">
                        <?= $this->Pcrn->tiempo_hace($row_evento->fecha_evento) ?>
                    </span>
                    <hr/>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>






