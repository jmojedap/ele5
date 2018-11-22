<?php 

    //Identificar categoría
        $categoria_id = 33;
        if ( $busqueda['cat'] > 0 ) { $categoria_id = $busqueda['cat']; }

    //Menú
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "items/explorar/",
            'atributos' => 'title="Explorar items"'
        );
            
        $arr_menus['listado'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Categorias',
            'link' => "items/listado",
            'atributos' => 'title="Editar item"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('listado', 'explorar');
        $elementos_rol[1] = array('listado', 'explorar');       
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);