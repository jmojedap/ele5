<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cargar' ) { $seccion = 'paginas'; }
        if ( $this->uri->segment(2) == 'agregar_pregunta' ) { $seccion = 'preguntas'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-search"></i>',
            'texto' => 'Explorar',
            'link' => "temas/explorar/",
            'atributos' => 'title="Explorar temas"'
        );
            
        $arr_menus['archivos'] = array(
            'icono' => '<i class="far fa-file"></i>',
            'texto' => 'Archivos',
            'link' => "temas/archivos/{$row->id}",
            'atributos' => 'title="Archivos"'
        );
            
        $arr_menus['quices'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Evidencias',
            'link' => "temas/quices/{$row->id}",
            'atributos' => 'title="Evidencias de aprendizaje"'
        );
            
        $arr_menus['links'] = array(
            'icono' => '<i class="fa fa-link"></i>',
            'texto' => 'Links',
            'link' => "temas/links/{$row->id}",
            'atributos' => 'title="Links"'
        );
            
        $arr_menus['programas'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Programas',
            'link' => "temas/programas/{$row->id}",
            'atributos' => 'title="Programas"'
        );
            
        $arr_menus['relacionados'] = array(
            'icono' => '<i class="fa fa-sitemap"></i>',
            'texto' => 'Temas UT',
            'link' => "temas/relacionados/{$row->id}",
            'atributos' => 'title="Temas relacionados con la Unidad Temática"'
        );
            
        $arr_menus['preguntas'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Preguntas',
            'link' => "temas/preguntas/{$row->id}",
            'atributos' => 'title="Preguntas"'
        );
            
        $arr_menus['paginas'] = array(
            'icono' => '<i class="far fa-file"></i>',
            'texto' => 'Páginas',
            'link' => "temas/paginas/{$row->id}",
            'atributos' => 'title="Páginas"'
        );
            
        $arr_menus['copiar'] = array(
            'icono' => '<i class="far fa-copy"></i>',
            'texto' => 'Clonar',
            'link' => "temas/copiar/{$row->id}",
            'atributos' => 'title="Crear una copia del programa"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil-alt"></i>',
            'texto' => 'Editar',
            'link' => "temas/editar/edit/{$row->id}",
            'atributos' => 'title="Editar tema"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'paginas', 'preguntas', 'programas', 'relacionados', 'quices', 'archivos', 'links', 'copiar', 'editar');
        $elementos_rol[1] = array('explorar', 'paginas', 'preguntas', 'programas', 'relacionados', 'quices', 'archivos', 'links', 'copiar', 'editar');
        $elementos_rol[2] = array('explorar', 'paginas', 'preguntas', 'programas', 'relacionados', 'quices', 'archivos', 'links', 'copiar', 'editar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);