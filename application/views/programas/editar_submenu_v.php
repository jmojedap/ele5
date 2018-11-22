<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases_sm[$seccion] = 'active';
    
    //Atributos de los elementos del menú    
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Datos',
            'link' => "programas/editar/edit/{$row->id}",
            'atributos' => 'title="Editar programa"'
        );
        
        $arr_menus['editar_temas'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Editar temas',
            'link' => "programas/editar_temas/edit/{$row->id}",
            'atributos' => 'title="Editar temas del programa"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('editar_temas', 'editar');
        $elementos_rol[1] = array('editar_temas', 'editar');
        $elementos_rol[2] = array('editar_temas', 'editar');
        $elementos_rol[3] = array('editar_temas', 'editar');
        $elementos_rol[4] = array('editar_temas', 'editar');
        
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);