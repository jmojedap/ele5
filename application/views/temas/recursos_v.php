<?php $this->load->view('assets/grocery_crud'); ?>
<?php $this->load->view('temas/menu_explorar_v'); ?>

<?php
        $seccion_sm = $this->uri->segment(2);

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['recursos_archivos'] = array(
            'icono' => '<i class="fa fa-file-o"></i>',
            'texto' => 'Archivos',
            'link' => "temas/recursos_archivos/",
            'atributos' => 'title="Archivos"'
        );
        
        $arr_menus['recursos_links'] = array(
            'icono' => '<i class="fa fa-link"></i>',
            'texto' => 'Links',
            'link' => "temas/recursos_links/",
            'atributos' => 'title="Links"'
        );
        
        $arr_menus['recursos_quices'] = array(
            'icono' => '<i class="fa fa-cube"></i>',
            'texto' => 'Quices',
            'link' => "temas/recursos_quices/",
            'atributos' => 'title="Quices"'
        );
        
        $arr_menus['recursos_preguntas'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Preguntas',
            'link' => "temas/recursos_preguntas/",
            'atributos' => 'title="Preguntas"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('recursos_archivos', 'recursos_links', 'recursos_quices');
        $elementos_rol[1] = array('recursos_archivos', 'recursos_links', 'recursos_quices');
        $elementos_rol[2] = array('recursos_archivos', 'recursos_links', 'recursos_quices');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);
        

    $clase_menu[$this->uri->segment(2)] = 'a3 actual';
    $nombre_tabla = $this->uri->segment(2);

    $this->load->view('comunes/gc_v');