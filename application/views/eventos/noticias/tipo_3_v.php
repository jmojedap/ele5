<?php
    $row_quiz = $this->Pcrn->registro_id('quiz', $row_noticia->referente_id);
    $row_tema = $this->Pcrn->registro_id('tema', $row_quiz->tema_id);
    $href = base_url("quices/iniciar/{$row_noticia->referente_id}");
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->creador_id}", $this->App_model->nombre_usuario($row_noticia->creador_id, 2), 'class="" title=""') ?>
</b>
<span class="suave"> programó una evidencia de aprendizaje</span>    
<br/>
<span class="suave" title="<?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'Y-M-d') ?>"><?= $this->Pcrn->tiempo_hace($row_noticia->creado); ?></span>

<a class="noticia_contenido" href="<?= $href ?>" target="_blank">
    <h4><?= $row_tema->nombre_tema ?></h4>
    <p>
        <span class="etiqueta nivel w1"><?= $row_quiz->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_quiz->area_id) ?>
    </p>
    <p>
        <span class="text-info">
            El quiz está programado para <?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'M d') ?>
        </span>
    </p>    
</a>

