<?php

    $clase_submenu = array(
        'nuevo' =>    '',
        'existente'   =>    ''
    );
    
    $clase_submenu[$subseccion] = 'active';
?>


<ul class="nav nav-pills sep1">
    <li class="<?= $clase_submenu['nuevo'] ?>">
        <?= anchor("programas/nuevo_flipbook/$row->id/nuevo", 'Nuevo') ?>
    </li>
    <li class="<?= $clase_submenu['existente'] ?>">
        <?= anchor("programas/nuevo_flipbook/$row->id/existente", 'Existente') ?>
    </li>
</ul>