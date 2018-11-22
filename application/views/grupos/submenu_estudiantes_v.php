<?php
        $seccion_sm = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['estudiantes'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Listado',
            'link' => "grupos/estudiantes/{$row->id}",
            'atributos' => 'title="Listado de estudiantes"'
        );
            
        $arr_menus['cargar_estudiantes'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Cargar',
            'link' => "grupos/cargar_estudiantes/{$row->id}",
            'atributos' => 'title="Editar grupo"'
        );
        
        $arr_menus['editar_estudiantes'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Editar estudiantes',
            'link' => "grupos/editar_estudiantes/edit/{$row->id}",
            'atributos' => 'title="Editar estudiantes del grupo"'
        );
        
        
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('estudiantes', 'cargar_estudiantes', 'editar_estudiantes');
        $elementos_rol[1] = array('estudiantes', 'cargar_estudiantes', 'editar_estudiantes');
        $elementos_rol[2] = array('estudiantes', 'cargar_estudiantes', 'editar_estudiantes');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);