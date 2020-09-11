<?php
    $clases_submenu[$subseccion] = 'active';
?>

<div class="mb-2">
    <ul class="nav nav-pills">
      <li role="presentation" class="<?= $clases_submenu['en_linea'] ?>">
          <?= anchor("grupos/cuestionarios_flipbooks/$row->id", 'Generar', 'class="' . $clase_submenu['en_linea'] . '"') ?>
      </li>
      <li role="presentation" class="<?= $clases[''] ?>">
          <?= anchor("grupos/cuestionarios/{$row->id}", '<i class="fa fa-"></i> Cuestionarios') ?>
      </li>
    </ul>      
</div>