<?php
    $clase_submenu = array(
        'listado' =>    'btn btn-default',
        'flipbooks_usuarios'   =>    'btn btn-default',
    );
    
    $clase_submenu[$subseccion] = 'btn btn-primary';
?>

<p>
    <?= anchor("instituciones/flipbooks/$row->id", "Listado", 'class="' . $clase_submenu['listado'] . '"') ?>
</p>