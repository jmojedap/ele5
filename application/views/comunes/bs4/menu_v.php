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
<div class="mb-3 d-none d-md-block">
    <ul class="nav nav-tabs nav-tabs-line">
        <?php foreach ($elementos as $elemento) : ?>
            <?php $arr_menu = $arr_menus[$elemento]; ?>
            <li role="presentation" class="nav-item">
                <a href="<?= base_url($arr_menu['link']) ?>" class="nav-link <?= $clases[$elemento] ?>" <?= $arr_menu['atributos'] ?>>
                    <?= $arr_menu['icono'] . ' ' .  $arr_menu['texto'] ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</div>

<!--Menú pequeño-->
<div class="d-sm-block d-md-none mb-1">
    <button class="btn btn-secondary btn-block" type="button" data-toggle="collapse" data-target="#lista-menu" aria-expanded="false" aria-controls="lista-menu">
        </i> &nbsp; <?= $arr_menus[$seccion]['texto'] ?>
        <i class="fa fa-chevron-down float-right"></i>
    </button>
    
    <ul class="list-group collapse" id="lista-menu" style="margin: 10px 0;">
        <?php foreach ($elementos as $elemento) : ?>
            <?php $arr_menu = $arr_menus[$elemento]; ?>
            <a href="<?= base_url($arr_menu['link']) ?>" class="list-group-item list-group-item-action <?= $clases[$elemento] ?>" <?= $arr_menu['atributos'] ?>>
                <?= $arr_menu['icono'] . ' ' .  $arr_menu['texto'] ?>
            </a>
        <?php endforeach ?>
    </ul>
</div>