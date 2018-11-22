<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'report_usuarios_01' ) { $seccion = 'reportes'; }
        if ( $this->uri->segment(2) == 'report_instituciones_01' ) { $seccion = 'reportes'; }
        if ( $this->uri->segment(2) == 'reporte_temas_02' ) { $seccion = 'reportes'; }
        if ( $this->uri->segment(2) == 'reporte_general' ) { $seccion = 'reportes'; }
        if ( $this->uri->segment(2) == 'reporte_programas_01' ) { $seccion = 'reportes'; }
        if ( $this->uri->segment(2) == 'reporte_quices_01' ) { $seccion = 'reportes'; }
        if ( $this->uri->segment(2) == 'reporte_links_01' ) { $seccion = 'reportes'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['tablas'] = array(
            'icono' => '<i class="fa fa-database"></i>',
            'texto' => 'Base de datos',
            'link' => "develop/tablas/item",
            'atributos' => 'title="Explorar tablas de la base de datos"'
        );
            
        $arr_menus['panel'] = array(
            'icono' => '<i class="fa fa-refresh"></i>',
            'texto' => 'Sincronizar',
            'link' => "sincro/panel/",
            'atributos' => 'title="Sincronización de base de datos local"'
        );
        
        $arr_menus['reportes'] = array(
            'icono' => '<i class="fa fa-refresh"></i>',
            'texto' => 'Reportes',
            'link' => "datos/report_usuarios_01",
            'atributos' => 'title="Reportes"'
        );
        
        $arr_menus['msexcel'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Exportar',
            'link' => "develop/msexcel/",
            'atributos' => 'title="Exportar datos de tablas a MS-Excek"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('tablas', 'panel', 'reportes', 'msexcel');
        $elementos_rol[1] = array('tablas', 'panel', 'reportes', 'msexcel');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);