<?php
    $row_usuario = $this->Pcrn->registro_id('usuario', $row_noticia->referente_id);
    $nombre_usuario = $this->App_model->nombre_usuario($row_noticia->usuario_id, 2);
?>
<b>
    <?= anchor("usuarios/actividad/{$row_noticia->usuario_id}", $nombre_usuario, 'class="" title=""') ?>
</b>
<p class="mt-2">
    <strong>
    <?= $this->App_model->nombre_usuario($row_noticia->creador_id, 2) ?>
    </strong>
    cambió el estado de pago del estudiante
    <strong>
        <?= $nombre_usuario ?>
    </strong>
    a <?php if ( $row_noticia->entero_1 == 1 ) : ?>
        <strong class="text-success">Sí</strong>
    <?php else : ?>
        <strong class="text-danger">No</strong>
    <?php endif; ?>
    .
</p>
<pre class="my-2 alert alert-info">
    <?= $row_noticia->descripcion ?>, 
</pre>
<span class="suave"><?= $this->pml->ago($row_noticia->editado); ?> &middot; <?= $this->pml->date_format($row_noticia->creado); ?></span>

