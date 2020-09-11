<?php
    $seccion_sm = $this->uri->segment(2);
    if ( $this->uri->segment(2) == 'importar_editar_anios_e' ) { $seccion_sm = 'importar_editar_anios'; }
    if ( $this->uri->segment(2) == 'desasignar_profesores_e' ) { $seccion_sm = 'desasignar_profesores'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['importar_editar_anios'] = array(
            'icono' => '<i class="far fa-calendar"></i>',
            'texto' => 'Años generación',
            'link' => "grupos/importar_editar_anios/",
            'atributos' => 'title="Explorar grupos"'
        );
        
        $arr_menus['desasignar_profesores'] = array(
            'icono' => '<i class="fa fa-user-times"></i>',
            'texto' => 'Desasignar profesores',
            'link' => "grupos/desasignar_profesores/",
            'atributos' => 'title="Desasignar profesores de grupos con archivo Excel"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('importar_editar_anios', 'desasignar_profesores');
        $elementos_rol[1] = array('importar_editar_anios', 'desasignar_profesores');
        $elementos_rol[2] = array('importar_editar_anios');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/bs4/submenu_v', $data_menu);