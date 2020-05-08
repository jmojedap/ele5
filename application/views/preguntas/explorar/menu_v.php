<?php
    //Clases menú
        $seccion = $this->uri->segment(2);
        if ( $seccion == 'importar_e' ) { $clases['importar'] = 'active'; }

        $clases[$seccion] = 'active';

    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "preguntas/explorar/?tp=01",
            'atributos' => 'title="Explorar grupos"'
        );
            
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-arrow-circle-up"></i>',
            'texto' => 'Importar',
            'link' => "preguntas/importar/",
            'atributos' => 'title="Importar datos de grupos"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'importar');
        $elementos_rol[1] = array('explorar', 'importar');
        $elementos_rol[2] = array('explorar');
        
        $elementos_rol[3] = array('explorar');
        $elementos_rol[4] = array('explorar');
        $elementos_rol[5] = array('explorar');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/bs4/menu_v', $data_menu);