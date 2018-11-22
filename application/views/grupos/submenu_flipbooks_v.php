<?php
        $seccion_sm = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'validar_asignacion_f' ) { $seccion = 'asignar_flipbook'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['anotaciones'] = array(
            'icono' => '',
            'texto' => 'Anotaciones',
            'link' => "grupos/anotaciones/{$row->id}",
            'atributos' => 'title="Anotaciones en los contenidos"'
        );
            
        $arr_menus['asignar_flipbook'] = array(
            'icono' => '',
            'texto' => '+ Contenido',
            'link' => "grupos/asignar_flipbook/{$row->id}",
            'atributos' => 'title="Asignar un contenido a los estudiantes del gruop"'
        );
        
        $arr_menus['quitar_flipbook'] = array(
            'icono' => '',
            'texto' => '- Contenido',
            'link' => "grupos/quitar_flipbook/{$row->id}",
            'atributos' => 'title="Quitar un contenido a los estudiantes del gruop"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('anotaciones', 'asignar_flipbook', 'quitar_flipbook');
        $elementos_rol[1] = array('anotaciones', 'asignar_flipbook', 'quitar_flipbook');
        $elementos_rol[2] = array('anotaciones');
        $elementos_rol[3] = array('anotaciones');
        $elementos_rol[4] = array('anotaciones');
        $elementos_rol[5] = array('anotaciones');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);