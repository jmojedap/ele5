<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-arrow-left"></i>',
            'texto' => 'Explorar',
            'link' => "preguntas/explorar/",
            'atributos' => 'title="Explorar preguntas"'
        );
            
        $arr_menus['detalle'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => 'Vista previa',
            'link' => "preguntas/detalle/{$row->id}",
            'atributos' => 'title="Detalle de la pregunta"'
        );
            
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionarios',
            'link' => "preguntas/cuestionarios/{$row->id}",
            'atributos' => 'title="Cuestionarios que incluyen la pregunta"'
        );
            
        $arr_menus['estadisticas'] = array(
            'icono' => '<i class="fa fa-chart-bar"></i>',
            'texto' => 'Estadísticas',
            'link' => "preguntas/estadisticas/{$row->id}",
            'atributos' => 'title="Estadísticas de la pregunta"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil-alt"></i>',
            'texto' => 'Editar',
            'link' => "preguntas/editar/{$row->id}",
            'atributos' => 'title="Editar pregunta"'
        );

        $arr_menus['version'] = array(
            'icono' => '<i class="fa fa-code-branch"></i>',
            'texto' => 'Versión propuesta',
            'link' => "preguntas/version/{$row->id}",
            'atributos' => 'title="Editar versión alterna de pregunta"'
        );

        $arr_menus['historial'] = array(
            'icono' => '<i class="far fa-clock"></i>',
            'texto' => 'Historial',
            'link' => "preguntas/historial/{$row->id}",
            'atributos' => 'title="Historial de edición de la pregunta"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar', 'version', 'historial');
        $elementos_rol[1] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar', 'version', 'historial');
        $elementos_rol[2] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar', 'version', 'historial');
        $elementos_rol[3] = array('explorar', 'detalle', 'estadisticas');
        $elementos_rol[4] = array('explorar', 'detalle', 'estadisticas');
        $elementos_rol[5] = array('explorar', 'detalle', 'estadisticas');
        $elementos_rol[7] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'version');
        $elementos_rol[8] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar');
        
        if ( $editable ) 
        {
            $elementos_rol[3][] = 'editar';
            $elementos_rol[4][] = 'editar';
            $elementos_rol[5][] = 'editar';
        }
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);