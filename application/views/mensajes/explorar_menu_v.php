<?php
        $seccion = $this->uri->segment(2);
        //if ( $seccion == 'asignar_temas_multi_e' ) { $seccion = 'asignar_temas_multi'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-search"></i>',
            'texto' => 'Explorar',
            'link' => "mensajes/explorar/",
            'atributos' => 'title="Explorar mensajes"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar');
        $elementos_rol[1] = array('explorar');
        $elementos_rol[2] = array('explorar');
        $elementos_rol[3] = array('explorar');
        $elementos_rol[4] = array('explorar');
        $elementos_rol[5] = array('explorar');
        $elementos_rol[6] = array('explorar');
        $elementos_rol[7] = array('explorar');
        $elementos_rol[8] = array('explorar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);