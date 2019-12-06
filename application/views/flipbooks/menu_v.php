<?php
    //Clases de menús activos e inactivos
        $seccion = $this->uri->segment(2);
        $clases[$seccion] = 'active';

        if ( $this->uri->segment(2) == 'cargar' ) { $clases['paginas'] = 'active'; }
        if ( $this->uri->segment(2) == 'ver_anotaciones' ) { $clases['anotaciones'] = 'active'; }
        if ( $this->uri->segment(2) == 'importar_programacion' ) { $clases['programar_temas'] = 'active'; }
        if ( $this->uri->segment(2) == 'importar_programacion_e' ) { $clases['programar_temas'] = 'active'; }

    //Atributos de los elementos del menú
        
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-search"></i>',
            'texto' => '',
            'link' => 'flipbooks/explorar',
            'atributos' => 'title="Explorar contenidos"',
        );

        $arr_menus['abrir'] = array(
            'icono' => '<i class="fa fa-external-link-alt"></i>',
            'texto' => 'Abrir',
            'link' => "flipbooks/abrir/{$row->id}",
            'atributos' => 'target="_blank"',
        );

        $arr_menus['temas'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Temas',
            'link' => "flipbooks/temas/{$row->id}",
            'atributos' => '',
        );

        $arr_menus['programar_temas'] = array(
            'icono' => '<i class="far fa-calendar"></i>',
            'texto' => 'Programar',
            'link' => "flipbooks/programar_temas/{$row->id}",
            'atributos' => '',
        );

        $arr_menus['paginas'] = array(
            'icono' => '<i class="far fa-file"></i>',
            'texto' => 'Páginas',
            'link' => "flipbooks/paginas/{$row->id}",
            'atributos' => '',
        );

        $arr_menus['crear_cuestionario'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionario',
            'link' => "flipbooks/crear_cuestionario/{$row->id}",
            'atributos' => '',
        );

        $arr_menus['aperturas'] = array(
            'icono' => '<i class="fa fa-eye"></i>',
            'texto' => 'Lectores',
            'link' => "flipbooks/aperturas/{$row->id}",
            'atributos' => '',
        );

        $arr_menus['asignados'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Asignados',
            'link' => "flipbooks/asignados/{$row->id}",
            'atributos' => '',
        );
            
        $arr_menus['anotaciones'] = array(
            'icono' => '<i class="far fa-sticky-note"></i>',
            'texto' => 'Anotaciones',
            'link' => "flipbooks/anotaciones/{$row->id}",
            'atributos' => '',
        );

        $arr_menus['copiar'] = array(
            'icono' => '<i class="far fa-clone"></i>',
            'texto' => 'Clonar',
            'link' => "flipbooks/copiar/{$row->id}",
            'atributos' => '',
        );

        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil-alt"></i>',
            'texto' => 'Editar',
            'link' => "flipbooks/editar/edit/{$row->id}",
            'atributos' => '',
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'abrir', 'temas', 'programar_temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');
        $elementos_rol[1] = array('explorar', 'abrir', 'temas', 'programar_temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');
        $elementos_rol[2] = array('explorar', 'abrir', 'temas', 'programar_temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');
        
        $elementos_rol[3] = array('crear_cuestionario', 'programar_temas', 'aperturas', 'anotaciones');
        $elementos_rol[4] = array('crear_cuestionario', 'programar_temas', 'aperturas', 'anotaciones');
        $elementos_rol[5] = array('crear_cuestionario', 'programar_temas', 'aperturas', 'anotaciones');
        
        $elementos_rol[6] = array('abrir');
        
        $elementos_rol[7] = array('crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones');
        $elementos_rol[8] = array('explorar', 'abrir', 'temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');    
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/bs4/menu_v', $data_menu);