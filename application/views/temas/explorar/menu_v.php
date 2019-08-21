<?php
        $seccion = $this->uri->segment(2);
        if ( $seccion == 'recursos_archivos' ) { $clases['recursos'] = 'active'; }
        if ( $seccion == 'recursos_links' ) { $clases['recursos'] = 'active'; }
        if ( $seccion == 'recursos_quices' ) { $clases['recursos'] = 'active'; }
        if ( $seccion == 'recursos_preguntas' ) { $clases['recursos'] = 'active'; }
        if ( $seccion == 'importar_ut' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'importar_ut_e' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'copiar_preguntas' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'copiar_preguntas_e' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'asignar_quices' ) { $clases['importar'] = 'active'; }
        if ( $seccion == 'asignar_quices_e' ) { $clases['importar'] = 'active'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "temas/explorar/",
            'atributos' => 'title="Explorar temas"'
        );
            
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "temas/nuevo/add/",
            'atributos' => 'title="Nuevo tema"'
        );
        
        $arr_menus['importar'] = array(
            'icono' => '<i class="fas fa-upload"></i>',
            'texto' => 'Importar',
            'link' => "temas/importar/",
            'atributos' => 'title="Importar temas desde archivo MS Excel"'
        );
        
        $arr_menus['recursos'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Recursos',
            'link' => "temas/recursos_archivos/",
            'atributos' => 'title="Recursos de los temas"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'nuevo', 'importar', 'recursos');
        $elementos_rol[1] = array('explorar', 'nuevo', 'importar', 'recursos');
        $elementos_rol[2] = array('explorar', 'nuevo', 'importar', 'recursos');
        
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);