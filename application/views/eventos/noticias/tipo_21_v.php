<?php
    $row_cuestionario = $this->Pcrn->registro_id('cuestionario', $row_noticia->referente_id);
    $href = base_url("cuestionarios/vista_previa/{$row_noticia->referente_id}");
    
    $tiempo_hace = $this->Pcrn->tiempo_hace($row_noticia->editado);
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->usuario_id}", $this->App_model->nombre_usuario($row_noticia->c_usuario_id, 2)) ?>
</b>
<span class="suave"> ha creado un cuestionario</span>    
<br/>
<span class="suave" title="<?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'Y-M-d') ?>"><?= $tiempo_hace ?></span>

<a class="noticia_contenido" href="<?= $href ?>" target="_blank">
    <h4><?= $row_cuestionario->nombre_cuestionario ?></h4>
    <p>
        <span class="etiqueta nivel w1"><?= $row_noticia->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_noticia->area_id) ?>
    </p>
    
    <p>
        Creó el cuestionario hace <?= $tiempo_hace ?>
    </p>
</a>

