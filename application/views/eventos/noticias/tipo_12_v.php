<?php
    $row_tema = $this->Pcrn->registro_id('tema', $row_noticia->referente_id);
    $row_flipbook = $this->Pcrn->registro_id('flipbook', $row_noticia->referente_2_id);
    $href = base_url() . "flipbooks/abrir_flipbook/{$row_noticia->referente_2_id}/{$row_noticia->entero_1}/{$row_noticia->referente_id}";
    
    $tiempo_hace = $this->Pcrn->tiempo_hace($row_noticia->editado);
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->usuario_id}", $this->App_model->nombre_usuario($row_noticia->creador_id, 2)) ?>
</b>
<span class="suave"> ha leído un tema</span>    
<br/>
<span class="suave" title="<?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'Y-M-d') ?>"><?= $tiempo_hace ?></span>

<a class="noticia_contenido" href="<?= $href ?>" target="_blank">
    <h4><?= $row_tema->nombre_tema ?></h4>
    <p>
        <span class="etiqueta nivel w1"><?= $row_noticia->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_noticia->area_id) ?>
    </p>
    
    <p>
        Leyó el tema hace <?= $tiempo_hace ?> del contenido <?= $row_flipbook->nombre_flipbook ?>
    </p>
</a>

