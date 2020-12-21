<?php

    $row_quiz = $this->Pcrn->registro_id('quiz', $row_noticia->referente_2_id);
    $row_ua = $this->Pcrn->registro_id('quiz', $row_noticia->referente_id);
    $link = base_url("usuarios/quices/{$row_noticia->usuario_id}");
    
    //Configuración de resultado
        $arr_resultados[0] = 'incorrectamente';
        $arr_resultados[1] = 'correctamente';
        
        $arr_clase[0] = 'alert-warning';
        $arr_clase[1] = 'alert-success';
    
        $arr_icono[0] = '<i class="fa fa-warning"></i>';
        $arr_icono[1] = '<i class="fa fa-check"></i>';
    
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->creador_id}", $this->App_model->nombre_usuario($row_noticia->creador_id, 2), 'class="" title=""') ?>
</b>
<span class="suave">respondió una evidencia de aprendizaje</span>    
<br/>
<span class="suave"><?= $this->Pcrn->tiempo_hace($row_noticia->editado); ?></span>

<a class="noticia_contenido" href="<?= $link ?>">
    <h4><?= $row_quiz->nombre_quiz ?></h4>
    <p>
        <span class="etiqueta nivel w1"><?= $row_noticia->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_noticia->area_id) ?>
    </p>
    <div class="alert <?= $arr_clase[$row_noticia->estado] ?>">
        <?= $arr_icono[$row_noticia->estado] ?>
        La evidencia fue respondida <?= $arr_resultados[$row_noticia->estado] ?>. Hizo <?= $row_noticia->entero_1 ?>  intentos.
    </div>
</a>

