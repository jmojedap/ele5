<?php
        $seccion = $this->uri->segment(2);
        
        if ( $seccion == 'asignar_taller' ) { $seccion = 'importar'; }
        if ( $seccion == 'asignar_taller_e' ) { $seccion = 'importar'; }
    
        $clases[$seccion] = 'active';
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "flipbooks/explorar/",
            'atributos' => 'title="Explorar contenidos"'
        );
        
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Asignar talleres',
            'link' => "flipbooks/asignar_taller/",
            'atributos' => 'title="Asignar taller a contenidos con archivo MS-Excel"'
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "flipbooks/nuevo/add/",
            'atributos' => 'title="Agregar un nuevo contenido"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'importar', 'nuevo');
        $elementos_rol[1] = array('explorar', 'importar', 'nuevo');
        $elementos_rol[2] = array('explorar', 'nuevo');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);
