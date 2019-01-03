<?php
    //Clases menú
        $seccion = $this->uri->segment(2);

        $clases[$seccion] = 'active';
        if ( $seccion == 'importar_asignaciones_e' ) { $clases['importar_asignaciones'] = 'active'; }

    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "cuestionarios/explorar/",
            'atributos' => ''
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "cuestionarios/nuevo/add/",
            'atributos' => 'title="Crear un nuevo cuestionario"'
        );

        $arr_menus['asignar_masivo'] = array(
            'icono' => '<i class="fa fa-file-excel"></i>',
            'texto' => 'Asignar',
            'link' => "cuestionarios/asignar_masivo/",
            'atributos' => 'title="Asignar cuestionarios mediante archivo Excel"'
        );

        $arr_menus['responder_masivo'] = array(
            'icono' => '<i class="fa fa-file-excel"></i>',
            'texto' => 'Cargar respuestas',
            'link' => "cuestionarios/responder_masivo/",
            'atributos' => 'title="Cargar respuestas con archivo Excel"'
        );

    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'nuevo', 'asignar_masivo', 'responder_masivo');
        $elementos_rol[1] = array('explorar', 'importar_asignaciones', 'nuevo');
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