<?php
    $seccion_sm = $this->uri->segment(2);
    if ( $this->uri->segment(2) == 'eliminar_por_username_e' ) { $seccion_sm = 'eliminar_por_username'; }
    if ( $this->uri->segment(2) == 'importar_estudiantes_e' ) { $seccion_sm = 'importar_estudiantes'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['importar_estudiantes'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Estudiantes',
            'link' => "usuarios/importar_estudiantes/",
            'atributos' => 'title="Importar estudiantes"'
        );
        
        $arr_menus['eliminar_por_username'] = array(
            'icono' => '<i class="fa fa-trash"></i>',
            'texto' => 'Eliminar por username',
            'link' => "usuarios/eliminar_por_username/",
            'atributos' => 'title="Eliminar por username"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('importar_estudiantes', 'eliminar_por_username');
        $elementos_rol[1] = array('importar_estudiantes', 'eliminar_por_username');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);