<?php
        $seccion = $this->uri->segment(2);
        if ( $seccion == 'importar_editar_anios' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'importar_editar_anios_e' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'desasignar_profesores' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'desasignar_profesores_e' ) { $clases['importar'] = 'active'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "grupos/explorar/",
            'atributos' => 'title="Explorar grupos"'
        );
            
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-arrow-circle-up"></i>',
            'texto' => 'Importar',
            'link' => "grupos/importar_editar_anios/",
            'atributos' => 'title="Importar datos de grupos"'
        );
            
        $arr_menus['panel_reportes'] = array(
            'icono' => '<i class="fa fa-arrow-circle-down"></i>',
            'texto' => 'Reportes',
            'link' => "grupos/panel_reportes/",
            'atributos' => 'title="Reportes de grupos"'
        );
            
        $arr_menus['explorar_evidencias'] = array(
            'icono' => '<i class="fa fa-calendar-check-o"></i>',
            'texto' => 'Evidencias',
            'link' => "grupos/explorar_evidencias/",
            'atributos' => 'title="Explorar evidencias de actividad"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'importar');
        $elementos_rol[1] = array('explorar', 'importar');
        $elementos_rol[2] = array('explorar');
        
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);