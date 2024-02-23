<?php
        
    //Clases menú
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'nuevo_post' ) { $seccion = 'posts'; }

        $clases[$seccion] = 'active';

    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "contenidos_ap/explorar/",
            'atributos' => ''
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "posts/editar/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['leer'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => 'Abrir',
            'link' => "posts/leer/{$row->id}",
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'editar', 'leer');
        $elementos_rol[1] = array('explorar', 'editar', 'leer');
        $elementos_rol[2] = array('explorar', 'editar', 'leer');
        
        $elementos_rol[3] = array('explorar', 'leer');
        $elementos_rol[4] = array('explorar', 'leer');
        $elementos_rol[5] = array('explorar', 'leer');
        
        $elementos_rol[6] = array('explorar', 'leer');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargar vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);