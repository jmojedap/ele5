<?php
    $row_tema = $this->Pcrn->registro_id('tema', $row_noticia->referente_id);
    $href = base_url() . "flipbooks/abrir_flipbook/{$row_noticia->referente_2_id}/{$row_noticia->entero_1}/{$row_noticia->referente_id}";
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->creador_id}", $this->App_model->nombre_flipbook($row_noticia->referente_2_id), 'class="" title=""') ?>
</b>
<span class="suave"> tienes programado un tema</span>    
<br/>
<span class="suave" title="<?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'Y-M-d') ?>"><?= $this->pml->ago($row_noticia->creado); ?></span>

<a class="noticia_contenido" href="<?= $href ?>" target="_blank">
    <h4><?= $row_tema->nombre_tema ?></h4>
    <p>
        <span class="etiqueta nivel w1"><?= $row_noticia->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_noticia->area_id) ?>
    </p>
    <p>
        <span class="text-info">
            El tema está programado para <?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'M d') ?>
        </span>
    </p>    
</a>

