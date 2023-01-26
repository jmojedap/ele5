<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'nuevo_grupo' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'cargar_grupos' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'cargar_grupos_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_estudiantes' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_estudiantes_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'asignar_profesores' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'asignar_profesores_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'vaciar_grupos' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'vaciar_grupos_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_usuarios' ) { $seccion = 'usuarios'; }
        if ( $this->uri->segment(2) == 'importar_usuarios_e' ) { $seccion = 'usuarios'; }
        
        if ( $this->uri->segment(2) == 'importar_grupos' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_grupos_e' ) { $seccion = 'grupos'; }
        
        if ( $this->uri->segment(2) == 'resctn_grupo' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen01' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen03' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen03' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_grupo' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_area' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_competencia' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_componente' ) { $seccion = 'cuestionarios'; }

        if ( $this->uri->segment(2) == 'editar' ) { $seccion = 'info'; }
        if ( $this->uri->segment(2) == 'eliminar_pre' ) { $seccion = 'info'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-arrow-left"></i>',
            'texto' => '',
            'link' => "instituciones/explorar/",
            'atributos' => 'title="Explorar instituciones"'
        );

        $arr_menus['info'] = array(
            'icono' => '',
            'texto' => 'Información',
            'link' => "instituciones/info/{$row->id}",
            'atributos' => 'title="Información sobre la institución"'
        );
            
        $arr_menus['grupos'] = array(
            'icono' => '',
            'texto' => 'Grupos',
            'link' => "instituciones/grupos/{$row->id}",
            'atributos' => 'title="Grupos de la institución"'
        );
        
        $arr_menus['usuarios'] = array(
            'icono' => '',
            'texto' => 'Usuarios',
            'link' => "instituciones/usuarios/{$row->id}",
            'atributos' => 'title="Usuarios institucionales"'
        );
        
        $arr_menus['flipbooks'] = array(
            'icono' => '',
            'texto' => 'Contenidos',
            'link' => "instituciones/flipbooks/{$row->id}",
            'atributos' => 'title="Contenidos asignados a estudiantes de la institución"'
        );
        
        $arr_menus['cuestionarios'] = array(
            'icono' => '',
            'texto' => 'Cuestionarios',
            'link' => "instituciones/cuestionarios/{$row->id}",
            'atributos' => 'title="Cuestionarios asignados a estudiantes de la institución"'
        );
        
        $arr_menus['procesos'] = array(
            'icono' => '',
            'texto' => 'Procesos',
            'link' => "instituciones/procesos/{$row->id}",
            'atributos' => 'title="Procesos sobre la institución"'
        );
        
        $arr_menus['mensajes_masivos'] = array(
            'icono' => '',
            'texto' => 'Mensajes',
            'link' => "instituciones/mensajes_masivos/{$row->id}",
            'atributos' => 'title="Mensajes masivos para usuarios de la institución"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'info', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'procesos', 'mensajes_masivos');
        $elementos_rol[1] = array('explorar', 'info', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'procesos', 'mensajes_masivos');
        $elementos_rol[2] = array('explorar', 'info', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'procesos', 'editar');
        $elementos_rol[3] = array('grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'mensajes_masivos');
        $elementos_rol[4] = array('grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'mensajes_masivos');
        $elementos_rol[5] = array('grupos');
        $elementos_rol[8] = array('explorar', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);