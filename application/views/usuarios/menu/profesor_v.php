<?php

        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cuestionarios_resumen01' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_area' ) { $seccion = 'cuestionarios'; }

        $clases[$seccion] = 'active';

        /*$mostrar_actividad = FALSE;

        if ( $this->session->userdata('rol_id') <= 5 ) { $mostrar_actividad = TRUE; }
        if ( $row->id == $this->session->userdata('usuario_id') ) { $mostrar_actividad = TRUE; }    //Si es él mismo*/
    
    //Atributos de los elementos del menú
        $arr_menus['actividad'] = array(
            'icono' => '<i class="fa fa-th-list"></i>',
            'texto' => 'Actividad',
            'link' => "usuarios/actividad/{$usuario_id}",
            'atributos' => ''
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil-alt"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editar/{$usuario_id}",
            'atributos' => ''
        );
            
        $arr_menus['grupos_profesor'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Grupos',
            'link' => "usuarios/grupos_profesor/{$usuario_id}",
            'atributos' => ''
        );

        $arr_menus['anotaciones'] = array(
            'icono' => '<i class="far fa-sticky-note"></i>',
            'texto' => 'Anotaciones',
            'link' => "usuarios/anotaciones/{$usuario_id}",
            'atributos' => ''
        );
            
        $arr_menus['contrasena'] = array(
            'icono' => '<i class="fa fa-lock"></i>',
            'texto' => 'Contraseña',
            'link' => "usuarios/contrasena/",
            'atributos' => 'title="Cambiar mi contraseña"'
        );
        
        $arr_menus['editarme'] = array(
            'icono' => '<i class="fa fa-pencil-alt"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editarme/{$usuario_id}",
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('actividad', 'grupos_profesor', 'editar', 'master_login');
        $elementos_rol[1] = array('actividad', 'grupos_profesor', 'editar', 'master_login');
        $elementos_rol[2] = array('actividad', 'grupos_profesor', 'editar');
        $elementos_rol[3] = array('actividad', 'anotaciones');
        $elementos_rol[4] = array('actividad', 'anotaciones');
        $elementos_rol[5] = array('actividad', 'anotaciones');
        $elementos_rol[6] = array('actividad');
        $elementos_rol[7] = array('actividad');
        $elementos_rol[8] = array('actividad');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Elementos especiales
        if ( $this->session->userdata('usuario_id') == $row->id ) 
        {
            //Si es él mismo perfil del usuario
            $elementos[] = 'editarme';
            $elementos[] = 'contrasena';
        }
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
?>

<?php $this->load->view('comunes/bs4/menu_v', $data_menu)?>
            
