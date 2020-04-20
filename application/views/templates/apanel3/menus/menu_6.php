<?php

//Biblioteca
    $opciones_menus['usuarios/biblioteca'] = array('biblioteca', '', '');
    $opciones_menus['usuarios/calendario'] = array('biblioteca', '', '');
    $opciones_menus['posts/ap_explorar'] = array('biblioteca', '', '');
    $opciones_menus['posts/ap_leer'] = array('biblioteca', '', '');

    //Mis desempeños
    $opciones_menus['usuarios/actividad'] = array('mis_desempenos', '', '');
    $opciones_menus['usuarios/anotaciones'] = array('mis_desempenos', '', '');
    $opciones_menus['usuarios/quices'] = array('mis_desempenos', '', '');
    $opciones_menus['usuarios/cuestionarios'] = array('mis_desempenos', '', '');

    //Mensajes
    $opciones_menus['mensajes/conversacion'] = array('mensajes', '', '');
    $opciones_menus['mensajes/explorar'] = array('mensajes', '', '');
    $opciones_menus['mensajes/mensajes'] = array('mensajes', '', '');

    //Contraseña
    $opciones_menus['usuarios/contrasena'] = array('contrasena', '', '');

    //Ayuda
    $opciones_menus['datos/ayudas'] = array('ayuda', '', '');

//Clases para menú izquierda
    $direccion = $this->uri->segment(1) . '/' . $this->uri->segment(2);
    $current_menu = $opciones_menus[$direccion];

    $m_current['menu'] = $current_menu[0];
    $m_current['submenu'] = $current_menu[1];
    $m_current['submenu_show'] = $current_menu[2];
        
    $clase_m[$m_current['menu']] = 'current';    //Clase menú
    $clase_sm[$m_current['submenu']] = 'current';    //Clase submenú

?>


<aside class="main_nav_col">
    
    <?php $this->load->view('templates/apanel3/parts/header') ?>
    <ul class="main_nav">
        
        <li class="">
            <a href="<?= base_url() ?>usuarios/biblioteca" class="<?= $clase_m['biblioteca'] ?>"><i class="fa fa-2x fa-home"></i><span>inicio</span></a>
        </li>
        
        <li class="">
            <a href="<?= base_url() ?>usuarios/quices/0" class="<?= $clase_m['mis_desempenos'] ?>"><i class="fa fa-2x fa-user"></i><span>mis desempeños</span></a>
            <?php if ( $m_current['menu'] == 'usuarios' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>
        </li>
        
        <li class="">
            <a href="<?= base_url() . 'mensajes/conversacion'?>" class="<?= $clase_m['mensajes'] ?>">
                <?php $this->load->view('plantilla_apanel/menu_mensajes_v'); ?>
            </a>
        </li>
        
        <li class="">
            <a href="<?= base_url() ?>usuarios/contrasena/" class="<?= $clase_m['contrasena'] ?>"><i class="fa fa-2x fa-lock"></i><span>contrasena</span></a>
        </li>
        
        <li class="">
            <a href="<?= base_url() . 'datos/ayudas/' ?>" class="<?= $clase_m['ayuda'] ?>" ><i class="fa fa-2x fa-question-circle"></i><span>ayuda</span></a>
        </li>
        

    </ul>
</aside>