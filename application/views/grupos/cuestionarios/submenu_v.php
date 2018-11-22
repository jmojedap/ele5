<?php
    $seccion_sm = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion_sm = 'seccion'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-check"></i>',
            'texto' => 'Resultados',
            'link' => "grupos/cuestionarios/{$row->id}",
            'atributos' => 'title="Resultados de los cuestionarios"'
        );
            
        $arr_menus['cuestionarios_resumen03'] = array(
            'icono' => '<i class="fa fa-bar-chart-o"></i>',
            'texto' => 'Competencias',
            'link' => "grupos/cuestionarios_resumen03/{$row->id}/50",
            'atributos' => 'title="Resumen por cometencias"'
        );
        
        $arr_menus['cuestionarios_resumen01'] = array(
            'icono' => '<i class="fa fa-bar-chart-o"></i>',
            'texto' => 'Competencias cuestionario',
            'link' => "grupos/cuestionarios_resumen01/{$row->id}/50",
            'atributos' => 'title="Resumen por cometencias por cuestionario"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('cuestionarios', 'cuestionarios_resumen03', 'cuestionarios_resumen01');
        $elementos_rol[1] = array('cuestionarios', 'cuestionarios_resume03', 'cuestionarios_resumen01');
        $elementos_rol[2] = array('cuestionarios', 'cuestionarios_resume03', 'cuestionarios_resumen01');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);