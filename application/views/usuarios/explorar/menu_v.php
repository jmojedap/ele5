<?php
    //Clases menú
        $seccion = $this->uri->segment(2);

        if ( $seccion == 'importar_estudiantes' ) { $seccion = 'importar'; }
        if ( $seccion == 'importar_estudiantes_e' ) { $seccion = 'importar'; }
        if ( $seccion == 'eliminar_por_username' ) { $seccion = 'importar'; }
        if ( $seccion == 'eliminar_por_username_e' ) { $seccion = 'importar'; }
    
        $clases[$seccion] = 'active';

    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-search"></i>',
            'texto' => 'Explorar',
            'link' => "usuarios/explorar/",
            'atributos' => 'title="Explorar contenidos"'
        );
        
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-upload"></i>',
            'texto' => 'Importar',
            'link' => "usuarios/importar_estudiantes/",
            'atributos' => 'title="Importar estudiantes con archivo Excel"'
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "usuarios/nuevo/estudiante/0/add",
            'atributos' => 'title="Agregar un nuevo usuario"'
        );

    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'nuevo', 'importar');
        $elementos_rol[1] = array('explorar', 'nuevo', 'importar');
        $elementos_rol[2] = array('explorar', 'nuevo');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/bs4/menu_v', $data_menu);