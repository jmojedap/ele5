<?php
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
    
    $arr_menus['biblioteca'] = array(
        'icono' => '<i class="fa fa-th-book"></i>',
        'texto' => 'Biblioteca',
        'link' => "usuarios/biblioteca",
        'atributos' => ''
    );
            
    $arr_menus['calendario'] = array(
        'icono' => '<i class="fa fa-calendar-o"></i>',
        'texto' => 'Programador',
        'link' => "eventos/calendario/",
        'atributos' => ''
    );
            
    $arr_menus['noticias'] = array(
        'icono' => '<i class="fa fa-newspaper-o"></i>',
        'texto' => 'Noticias',
        'link' => "eventos/noticias/",
        'atributos' => ''
    );
            
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('calendario', 'noticias');
        $elementos_rol[1] = array('calendario', 'noticias');
        $elementos_rol[2] = array('calendario', 'noticias');
        $elementos_rol[3] = array('biblioteca', 'calendario', 'noticias');
        $elementos_rol[4] = array('biblioteca', 'calendario', 'noticias');
        $elementos_rol[5] = array('biblioteca', 'calendario', 'noticias');
        $elementos_rol[6] = array('biblioteca', 'calendario', 'noticias');
        $elementos_rol[7] = array('calendario', 'noticias');
        $elementos_rol[8] = array('calendario', 'noticias');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Elementos especiales
        if ( $this->session->userdata('usuario_id') == $row->id ) {
            //Si es él mismo perfil del usuario
            $elementos[] = 'editarme';
            $elementos[] = 'contrasena';
        }
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
        
    //Cargar vista
        $this->load->view('comunes/menu_v', $data_menu);
?>