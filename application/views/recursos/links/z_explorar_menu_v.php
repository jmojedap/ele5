<?php
    $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['links'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "recursos/links/",
            'atributos' => 'title="Explorar links"'
        );
        
        $arr_menus['importar_links'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Importar',
            'link' => "recursos/importar_links/",
            'atributos' => 'title="Importar links desde archivo MS-Excel"'
        );

        $arr_menus['eliminar_links'] = array(
            'icono' => '<i class="fa fa-trash"></i>',
            'texto' => 'Eliminar',
            'link' => "recursos/eliminar_links/",
            'atributos' => 'title="Eliminar links masivamente por temas desde archivo Excel"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('links', 'importar_links', 'eliminar_links');
        $elementos_rol[1] = array('links', 'importar_links', 'eliminar_links');
        $elementos_rol[2] = array('links', 'importar_links');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);