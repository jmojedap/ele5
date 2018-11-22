<?php
    //Clases menú
        $seccion = $this->uri->segment(2);

        $clases[$seccion] = 'active';
        if ( $this->input->get('f1') == 1 ) { $clases['ap_tutoriales'] = 'active'; }
        if ( $this->input->get('f1') == 2 ) { $clases['ap_atlas'] = 'active'; }
        if ( $this->input->get('f1') == 3 ) { $clases['ap_lectura_critica'] = 'active'; }

    
    //Atributos de los elementos del menú
        $arr_menus['biblioteca'] = array(
            'icono' => '<i class="fa fa-caret-left"></i>',
            'texto' => 'Biblioteca',
            'link' => "usuarios/biblioteca",
            'atributos' => ''
        );
        
        $arr_menus['ap_tutoriales'] = array(
            'icono' => '',
            'texto' => 'Tutoriales',
            'link' => "posts/ap_explorar/?f1=1",
            'atributos' => ''
        );
        
        $arr_menus['ap_atlas'] = array(
            'icono' => '',
            'texto' => 'Atlas',
            'link' => "posts/ap_explorar/?f1=2",
            'atributos' => 'title="Importar asignaciones de contenidos de acompañamiento pedagógico"'
        );
        
        $arr_menus['ap_lectura_critica'] = array(
            'icono' => '',
            'texto' => 'Lectura crítica',
            'link' => "posts/ap_explorar/?f1=3",
            'atributos' => ''
        );

    //Elementos de menú para cada rol
        $elementos_rol[3] = array('biblioteca', 'ap_tutoriales', 'ap_atlas', 'ap_lectura_critica');
        $elementos_rol[4] = array('biblioteca', 'ap_tutoriales', 'ap_atlas', 'ap_lectura_critica');
        $elementos_rol[5] = array('biblioteca', 'ap_tutoriales', 'ap_atlas', 'ap_lectura_critica');
        
        $elementos_rol[6] = array('biblioteca', 'ap_tutoriales', 'ap_atlas', 'ap_lectura_critica');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista menú
        $this->load->view('comunes/menu_v', $data_menu);