<?php
    //Clases menú
        $seccion = $this->uri->segment(2);

        $clases[$seccion] = 'active';
        if ( $seccion == 'ap_importar_asignaciones_e' ) { $clases['ap_importar_asignaciones'] = 'active'; }

    
    //Atributos de los elementos del menú
        $arr_menus['ap_explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "posts/ap_explorar/",
            'atributos' => ''
        );
        
        $arr_menus['ap_importar_asignaciones'] = array(
            'icono' => '<i class="fa fa-upload"></i>',
            'texto' => 'Importar',
            'link' => "posts/ap_importar_asignaciones/",
            'atributos' => 'title="Importar asignaciones de contenidos de acompañamiento pedagógico"'
        );
        
        $arr_menus['ap_nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "posts/ap_nuevo/",
            'atributos' => ''
        );

    //Elementos de menú para cada rol
        $elementos_rol[0] = array('ap_explorar', 'ap_importar_asignaciones', 'ap_nuevo');
        $elementos_rol[1] = array('ap_explorar', 'ap_importar_asignaciones', 'ap_nuevo');
        $elementos_rol[2] = array('ap_explorar', 'ap_nuevo');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/bs4/menu_v', $data_menu);