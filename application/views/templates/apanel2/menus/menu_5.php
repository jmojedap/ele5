<?php

    //Biblioteca
        $opciones_menus['usuarios/biblioteca'] = array('biblioteca', '', '');
        $opciones_menus['posts/ap_explorar'] = array('biblioteca', '', '');
        $opciones_menus['posts/ap_leer'] = array('biblioteca', '', '');

    //Grupos
        $opciones_menus['instituciones/grupos'] = array('grupos', '', '');
        $opciones_menus['grupos/estudiantes'] = array('grupos', '', '');
        $opciones_menus['grupos/anotaciones'] = array('grupos', '', '');
        $opciones_menus['grupos/quices'] = array('grupos', '', '');
        $opciones_menus['grupos/cuestionarios_flipbooks'] = array('grupos', '', '');
        $opciones_menus['grupos/cuestionarios'] = array('grupos', '', '');
        $opciones_menus['grupos/cuestionarios_resumen03'] = array('grupos', '', '');
        $opciones_menus['grupos/cuestionarios_resumen01'] = array('grupos', '', '');
        $opciones_menus['grupos/estudiantes'] = array('grupos', '', '');
        $opciones_menus['grupos/estudiantes'] = array('grupos', '', '');
        $opciones_menus['usuarios/actividad'] = array('grupos', '', '');

    //Flipbooks
        $opciones_menus['flipbooks/nuevo_cuestionario'] = array('cuestionarios', '', '');
        $opciones_menus['flipbooks/aperturas'] = array('biblioteca', '', '');

    //Cuestionarios
        $opciones_menus['cuestionarios/explorar'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
        $opciones_menus['cuestionarios/nuevo'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
        $opciones_menus['cuestionarios/vista_previa'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
        $opciones_menus['cuestionarios/asignar'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
        $opciones_menus['cuestionarios/preguntas'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
        $opciones_menus['cuestionarios/grupos'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
        $opciones_menus['cuestionarios/editar'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
        
        $opciones_menus['preguntas/explorar'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');
        $opciones_menus['preguntas/detalle'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');
        $opciones_menus['preguntas/cuestionarios'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');
        $opciones_menus['preguntas/editar'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');

    //Mensajes
        $opciones_menus['mensajes/conversacion'] = array('mensajes', '', '');
        $opciones_menus['mensajes/explorar'] = array('mensajes', '', '');

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
    
    <?php $this->load->view('templates/apanel2/encabezado') ?>
    <ul class="main_nav">
        
        <li class="">
            <a href="<?= base_url() ?>usuarios/biblioteca" class="<?= $clase_m['biblioteca'] ?>"><i class="fa fa-2x fa-home"></i><span>inicio</span></a>
        </li>

        <li class="">
            <a href="<?= base_url() . 'instituciones/grupos'?>" class="<?= $clase_m['grupos'] ?>">
                <i class="fa fa-2x fa-users"></i>
                <span>grupos</span>
            </a>
        </li>
        
        <li class="has_submenu">
            <a href="#" class="<?= $clase_m['cuestionarios'] ?>">
                <i class="fa fa-2x fa-question"></i>
                <span>cuestionarios</span>
            </a>
            
            <?php if ( $m_current['menu'] == 'cuestionarios' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>

            <ul class="sub_nav">
                <li>
                    <a href="<?= base_url('cuestionarios/explorar/?f1=1') ?>" class="<?= $clase_sm['cuestionarios-cuestionarios'] ?>">
                        <i class="fa fa-book"></i>
                        <span>cuestionarios</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('preguntas/explorar')?>" class="<?= $clase_sm['cuestionarios-preguntas'] ?>">
                        <i class="fa fa-question"></i>
                        <span>preguntas</span>
                    </a>
                </li>
            </ul>

        </li>
        
        <li class="">
            <a href="<?= base_url() . 'mensajes/conversacion'?>" class="<?= $clase_m['mensajes'] ?>">
                <?php $this->load->view('plantilla_apanel/menu_mensajes_v'); ?>
            </a>
        </li>
        
        <li class="">
            <a href="<?= base_url() . 'usuarios/contrasena'?>" class="<?= $clase_m['contrasena'] ?>"><i class="fa fa-2x fa-lock"></i><span>contraseña</span></a>
        </li>
        
        <li class="">
            <a href="<?= base_url() . 'datos/ayudas/' ?>" class="<?= $clase_m['ayuda'] ?>" ><i class="fa fa-2x fa-question-circle"></i><span>ayuda</span></a>
        </li>
        

    </ul>
</aside>