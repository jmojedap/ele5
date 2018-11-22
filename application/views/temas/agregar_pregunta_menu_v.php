<?php
    $seccion = 'existente';
    
    $clases['nueva'] = '';
    $clases['existente'] = '';
    
    if ( $proceso == 'add' ) { $seccion = 'nueva'; }
    
    $clases[$seccion] = 'active';
    
?>

<div class="sep2">
    <ul class="nav nav-pills">
      <li role="presentation" class="<?= $clases['nueva'] ?>">
          <?= anchor("temas/agregar_pregunta/{$row->id}/{$orden}/add", 'Nueva pregunta', 'class="' . $clases['nueva'] . '" title="Crear una nueva pregunta"') ?>
      </li>
      <li role="presentation" class="<?= $clases['existente'] ?>">
          <?= anchor("temas/agregar_pregunta/{$row->id}/{$orden}", 'Pregunta existente', 'title="Buscar una pregunta existente para asignársela al tema"') ?>
      </li>
    </ul>
</div>
