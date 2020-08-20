<?php

    $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'ayudas_nuevo' ) { $seccion = 'nuevo'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['ayudas_explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "datos/ayudas_explorar/",
            'atributos' => 'title="Explorar artículos de ayuda"'
        );
        
        $arr_menus['ayudas'] = array(
            'icono' => '<i class="fa fa-question-circle"></i>',
            'texto' => 'Ayudas',
            'link' => "datos/ayudas/",
            'atributos' => 'title="Buscar artículos de ayuda"'
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "datos/ayudas_nuevo/add/",
            'atributos' => 'title="Agregar un nuevo artículo de ayuda"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('ayudas_explorar', 'ayudas', 'nuevo');
        $elementos_rol[1] = array('ayudas_explorar', 'ayudas', 'nuevo');
        $elementos_rol[2] = array('ayudas');
        $elementos_rol[3] = array('ayudas');
        $elementos_rol[4] = array('ayudas');
        $elementos_rol[5] = array('ayudas');
        $elementos_rol[6] = array('ayudas');
        $elementos_rol[7] = array('ayudas');
        $elementos_rol[8] = array('ayudas');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);