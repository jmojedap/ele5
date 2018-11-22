<?php
        $seccion = $this->uri->segment(2);
        if ( $seccion == 'asignar_temas_multi_e' ) { $seccion = 'asignar_temas_multi'; }
        if ( $seccion == 'generar_flipbooks_multi_e' ) { $seccion = 'generar_flipbooks_multi'; }
        if ( $seccion == 'importar_e' ) { $seccion = 'importar'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "programas/explorar/",
            'atributos' => 'title="Explorar programas"'
        );
            
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Importar',
            'link' => "programas/importar/",
            'atributos' => 'title="Importar programas"'
        );
        
        $arr_menus['asignar_temas_multi'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Asignar temas',
            'link' => "programas/asignar_temas_multi/",
            'atributos' => 'title="Asignar temas con archivo MS Excel"'
        );
        
        $arr_menus['generar_flipbooks_multi'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Generar contenidos',
            'link' => "programas/generar_flipbooks_multi/",
            'atributos' => 'title="Generar Contenidos desde Programas"'
        );
        
        $arr_menus['vaciar'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Vaciar temas',
            'link' => "programas/vaciar/",
            'atributos' => 'title="Vaciar los temas asignados a los programas, con archivo MS-Excel"'
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "programas/nuevo/add/",
            'atributos' => 'title="Agregar un nuevo usuario"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'importar', 'asignar_temas_multi', 'generar_flipbooks_multi', 'vaciar', 'nuevo');
        $elementos_rol[1] = array('explorar', 'importar', 'asignar_temas_multi', 'generar_flipbooks_multi', 'vaciar', 'nuevo');
        $elementos_rol[2] = array('explorar', 'importar', 'asignar_temas_multi', 'generar_flipbooks_multi', 'nuevo');
        $elementos_rol[3] = array('explorar', 'nuevo');
        $elementos_rol[4] = array('explorar', 'nuevo');
        $elementos_rol[8] = array('explorar', 'importar', 'asignar_temas_multi', 'nuevo');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);