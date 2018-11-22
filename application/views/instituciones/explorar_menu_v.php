<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "instituciones/explorar/",
            'atributos' => 'title="Explorar instituciones"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Editar',
            'link' => "instituciones/editar/edit/{$row->id}",
            'atributos' => 'title="Editar institución"'
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "instituciones/nuevo/add/",
            'atributos' => 'title="Agregar una nueva institución"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'nuevo');
        $elementos_rol[1] = array('explorar', 'nuevo');
        $elementos_rol[2] = array('explorar', 'nuevo');
        $elementos_rol[8] = array('explorar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);