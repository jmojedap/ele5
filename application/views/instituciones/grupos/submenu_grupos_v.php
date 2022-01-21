<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'importar_grupos_e' ) { $seccion = 'importar_grupos'; }
        if ( $this->uri->segment(2) == 'importar_estudiantes_e' ) { $seccion = 'importar_estudiantes'; }
        if ( $this->uri->segment(2) == 'asignar_profesores_e' ) { $seccion = 'asignar_profesores'; }
        if ( $this->uri->segment(2) == 'vaciar_grupos_e' ) { $seccion = 'vaciar_grupos'; }

        $clases_sm[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['grupos'] = array(
            'icono' => '<i class="fa fa-list"></i>',
            'texto' => 'Listado',
            'link' => "instituciones/grupos/{$row->id}",
            'atributos' => 'title="Grupos de la institución"'
        );
            
        $arr_menus['nuevo_grupo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "instituciones/nuevo_grupo/add/{$row->id}",
            'atributos' => 'title="Agregar grupo a la institución"'
        );
        
        $arr_menus['importar_grupos'] = array(
            'icono' => '<i class="fa fa-upload"></i>',
            'texto' => 'Importar grupos',
            'link' => "instituciones/importar_grupos/{$row->id}",
            'atributos' => 'title="Imporgar grupos desde archivo Excel"'
        );
        
        $arr_menus['asignar_profesores'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Asignar profesores',
            'link' => "instituciones/asignar_profesores/{$row->id}",
            'atributos' => 'title="Asignar profesores con archivo MS-Excel"'
        );
            
        $arr_menus['vaciar_grupos'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Vaciar grupos',
            'link' => "instituciones/vaciar_grupos/{$row->id}",
            'atributos' => 'title="Eliminar masivamente los estudiantes de un listado de grupos"'
        );

        $arr_menus['usuarios'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Usuarios',
            'link' => "instituciones/usuarios/{$row->id}",
            'atributos' => 'title="Usuarios de la institucion"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('grupos', 'nuevo_grupo', 'importar_grupos', 'asignar_profesores', 'vaciar_grupos');
        $elementos_rol[1] = array('grupos', 'nuevo_grupo', 'importar_grupos', 'asignar_profesores', 'vaciar_grupos');
        $elementos_rol[2] = array('grupos', 'nuevo_grupo', 'importar_grupos', 'asignar_profesores', 'vaciar_grupos');
        $elementos_rol[3] = array('grupos', 'usuarios');
        $elementos_rol[4] = array('grupos', 'usuarios');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/submenu_v', $data_menu);