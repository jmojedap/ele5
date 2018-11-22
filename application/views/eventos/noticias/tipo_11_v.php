<?php
    

    $row_ctn = $this->Pcrn->registro_id('cuestionario', $row_noticia->referente_2_id);
    $row_uc = $this->Pcrn->registro_id('usuario_cuestionario', $row_noticia->referente_id);
    
    $porcentaje = 0;
    if ( strlen($row_uc->resumen) > 0 ) {
        $resumen = json_decode($row_uc->resumen);
        $porcentaje =  $this->Pcrn->int_percent($resumen->total[0], $resumen->total[1]);
    }
    
    $link = base_url() . "usuarios/resultados/{$row_noticia->usuario_id}/{$row_noticia->referente_id}";
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->c_usuario_id}", $this->App_model->nombre_usuario($row_noticia->c_usuario_id, 2), 'class="" title=""') ?>
</b>
<span class="suave">finalizó un cuestionario</span>    
<br/>
<span class="suave"><?= $this->Pcrn->tiempo_hace($row_noticia->creado); ?></span>

<a class="noticia_contenido" href="<?= $link ?>">
    <h4><?= $row_ctn->nombre_cuestionario ?></h4>
    <p>
        <span class="etiqueta nivel w1"><?= $row_noticia->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_noticia->area_id) ?>
    </p>
    <p>
        <span class="resaltar"><?= $porcentaje ?>%</span> de sus respuestas fueron correctas.
    </p>
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentaje ?>%;">
            <?= $porcentaje ?>%
        </div>
    </div>
</a>

