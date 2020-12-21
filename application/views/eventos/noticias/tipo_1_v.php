<?php
    $row_ctn = $this->Pcrn->registro_id('cuestionario', $row_noticia->referente_2_id);
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->creador_id}", $this->App_model->nombre_usuario($row_noticia->creador_id, 2), 'class="" title=""') ?>
</b>
<span class="suave">te asignó un cuestionario</span>    
<br/>
<span class="suave"><?= $this->Pcrn->tiempo_hace($row_noticia->creado); ?></span>

<a class="noticia_contenido" href="<?= base_url("cuestionarios/preliminar/{$row_noticia->referente_id}/noticias") ?>">
    <h4><?= $row_ctn->nombre_cuestionario ?></h4>
    <p>
        <span class="etiqueta nivel w1"><?= $row_noticia->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_noticia->area_id) ?>
    </p>
    <p>
        Tienes plazo de responderlo hasta
        <span class="text-info">
            <?= $this->Pcrn->fecha_formato($row_noticia->fecha_fin, 'M d') ?>
        </span>
    </p>    
</a>

