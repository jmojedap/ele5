<?php

    $seccion_sm = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion_sm = 'seccion'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['login_usuarios_ciudad'] = array(
            'icono' => '',
            'texto' => '% Ciudad',
            'link' => "estadisticas/login_usuarios_ciudad/",
            'atributos' => 'title="Por ciudad, % usuarios vs. % login"'
        );
        
        $arr_menus['login_instituciones'] = array(
            'icono' => '',
            'texto' => 'Por institución',
            'link' => "estadisticas/login_instituciones/",
            'atributos' => 'title="Cantidad de login por instituciones"'
        );
        
        $arr_menus['login_usuarios'] = array(
            'icono' => '',
            'texto' => 'Por usuario',
            'link' => "estadisticas/login_usuarios/",
            'atributos' => 'title="Cantidad de login por usuario"'
        );
        
        $arr_menus['login_diario'] = array(
            'icono' => '',
            'texto' => 'Por día',
            'link' => "estadisticas/login_diario/",
            'atributos' => 'title="Login de usuarios por día"'
        );
            
        $arr_menus['login_nivel'] = array(
            'icono' => '',
            'texto' => 'Por nivel',
            'link' => 'estadisticas/login_nivel',
            'atributos' => 'title="Cantidad de login de usuarios por nivel escolar"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('login_usuarios_ciudad', 'login_instituciones', 'login_usuarios', 'login_diario', 'login_nivel');
        $elementos_rol[1] = array('login_usuarios_ciudad', 'login_instituciones', 'login_usuarios', 'login_diario', 'login_nivel');
        $elementos_rol[2] = array('login_usuarios_ciudad', 'login_instituciones', 'login_usuarios', 'login_diario', 'login_nivel');
        $elementos_rol[3] = array('login_diario', 'login_nivel');
        $elementos_rol[4] = array('login_usuarios', 'login_diario', 'login_nivel');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);