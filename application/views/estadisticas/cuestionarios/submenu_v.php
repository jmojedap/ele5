<?php

    $seccion_sm = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'ctn_correctas_incorrectas' ) { $seccion_sm = 'cuestionarios'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['respuesta_cuestionarios'] = array(
            'icono' => '',
            'texto' => 'Respondidas',
            'link' => "estadisticas/cuestionarios_nivel/",
            'atributos' => 'title="Cantidad apreturas de contenidos por nivel"'
        );
        
        $arr_menus['ctn_correctas_incorrectas'] = array(
            'icono' => '',
            'texto' => 'Correctas - Incorrectas',
            'link' => "estadisticas/ctn_correctas_incorrectas/",
            'atributos' => 'title="Cantidad de correctas e incorrectas"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('respuesta_cuestionarios', 'ctn_correctas_incorrectas');
        $elementos_rol[1] = array('respuesta_cuestionarios', 'ctn_correctas_incorrectas');
        $elementos_rol[2] = array('respuesta_cuestionarios', 'ctn_correctas_incorrectas');
        $elementos_rol[4] = array('respuesta_cuestionarios', 'ctn_correctas_incorrectas');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);