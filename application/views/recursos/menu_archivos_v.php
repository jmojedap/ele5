<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'asignar_e' ) { $seccion = 'asignar'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['archivos'] = array(
            'icono' => '',
            'texto' => 'Explorar',
            'link' => "recursos/archivos/",
            'atributos' => 'title="Explorar archivos"'
        );
            
        $arr_menus['asignar'] = array(
            'icono' => '',
            'texto' => 'Asignar',
            'link' => 'recursos/asignar',
            'atributos' => 'title="Cargar listado de archivos para temas - MS Excel"'
        );
        
        $arr_menus['procesos_archivos'] = array(
            'icono' => '',
            'texto' => 'Procesos',
            'link' => 'recursos/procesos_archivos',
            'atributos' => 'title="Procesos con archivos"'
        );
        
        $arr_menus['archivos_no_asignados'] = array(
            'icono' => '',
            'texto' => 'No asignados',
            'link' => 'recursos/archivos_no_asignados',
            'atributos' => 'title="Asociar archivos por código"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('archivos', 'asignar', 'procesos_archivos', 'archivos_no_asignados');
        $elementos_rol[1] = array('archivos', 'asignar', 'procesos_archivos', 'archivos_no_asignados');
        $elementos_rol[2] = array('archivos', 'asignar', 'procesos_archivos', 'archivos_no_asignados');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);