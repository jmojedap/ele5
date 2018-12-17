<?php
    /*
     * Vista para construcción automática de los menús de los elementos
     * Necesita tres variables de entrada:
     * 1) $elementos, array con listado de elementos que se van a mostrar, puede variar según el rol de usuario
     * 2) $arr_menus, Array de menus, array con los arrays de atributos de cada elemento del menú
     * 3) $clases, Array de clases, de cada elemento del menú, determinar si está active o no, el índice corresponde al nombre del elemento
     */
?>

<!--Menú grande-->
<div class="hidden-xs hidden-sm sep2">
    <ul class="nav nav-tabs">
        <?php foreach ($elementos as $elemento) : ?>
            <?php $arr_menu = $arr_menus[$elemento]; ?>
            <li role="presentation" class="<?= $clases[$elemento] ?>">
                <?= anchor($arr_menu['link'], $arr_menu['icono'] . ' ' .  $arr_menu['texto'], $arr_menu['atributos']) ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>

<!--Menú pequeño-->
<div class="visible-xs visible-sm sep2">
    <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <i class="fa fa-bars"></i> &nbsp; <?= $arr_menus[$seccion]['texto'] ?>
    </button>
    
    <div class="panel collapse" id="collapseExample">
        
        <ul class="nav nav-pills nav-stacked">
            <?php foreach ($elementos as $elemento) : ?>
                <?php $arr_menu = $arr_menus[$elemento]; ?>
                <li role="presentation" class="<?= $clases[$elemento] ?>  <?= $clases_permiso[$key] ?>">
                    <?= anchor($arr_menu['link'], $arr_menu['icono'] . ' ' .  $arr_menu['texto'], $arr_menu['atributos']) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div> 
</div>