<?php

    $seccion_sm = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion_sm = 'seccion'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['quices_nivel'] = array(
            'icono' => '',
            'texto' => 'Por nivel',
            'link' => "estadisticas/quices_nivel/",
            'atributos' => 'title="Cantidad apreturas de contenidos por nivel"'
        );
        
        $arr_menus['quices_area'] = array(
            'icono' => '',
            'texto' => 'Por área',
            'link' => "estadisticas/quices_area/",
            'atributos' => 'title="Cantidad apreturas de contenidos por área"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('quices_nivel', 'quices_area');
        $elementos_rol[1] = array('quices_nivel', 'quices_area');
        $elementos_rol[2] = array('quices_nivel', 'quices_area');
        $elementos_rol[3] = array('quices_nivel', 'quices_area');
        $elementos_rol[4] = array('quices_nivel', 'quices_area');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);