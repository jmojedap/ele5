<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'importar_usuarios_e' ) { $seccion = 'importar_usuarios'; }

        $clases_sm[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['usuarios'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Listado',
            'link' => "instituciones/usuarios/{$row->id}",
            'atributos' => 'title="Usuarios de la institución"'
        );
            
        $arr_menus['importar_usuarios'] = array(
            'icono' => '<i class="fa fa-arrow-circle-up"></i>',
            'texto' => 'Importar',
            'link' => "instituciones/importar_usuarios/{$row->id}",
            'atributos' => 'title="Importar instituciones"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('usuarios', 'importar_usuarios');
        $elementos_rol[1] = array('usuarios', 'importar_usuarios');
        $elementos_rol[2] = array('usuarios', 'importar_usuarios');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);