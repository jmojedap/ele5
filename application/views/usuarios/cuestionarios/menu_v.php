<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'cuestionarios_resumen01' ) { $seccion = 'cuestionarios'; }

        $clases_sm[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Listado',
            'link' => "usuarios/cuestionarios/{$row->id}",
            'atributos' => 'title="Listado de cuestionarios"'
        );
            
        $arr_menus['cuestionarios_resumen01'] = array(
            'icono' => '<i class="fa fa-bar-chart-line"></i>',
            'texto' => 'Por competencias',
            'link' => "usuarios/cuestionarios_resumen01/{$row->id}",
            'atributos' => ''
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[1] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[2] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[3] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[4] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[5] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[6] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[7] = array('cuestionarios', 'cuestionarios_resumen01');
        $elementos_rol[8] = array('cuestionarios', 'cuestionarios_resumen01');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;

    //Cargar vista
        $this->load->view('comunes/bs4/submenu_v', $data_menu);

