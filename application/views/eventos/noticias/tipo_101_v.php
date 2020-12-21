<?php
    $row_usuario = $this->Pcrn->registro_id('usuario', $row_noticia->referente_id);
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->creador_id}", $this->App_model->nombre_usuario($row_noticia->creador_id, 2), 'class="" title=""') ?>
</b>
<span class="suave">ingresó a la Plataforma</span>    
<br/>
<span class="suave"><?= $this->Pcrn->tiempo_hace($row_noticia->editado); ?></span>

