<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['enunciados'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "datos/enunciados/",
            'atributos' => 'title="Explorar enunciados"'
        );
        
        $arr_menus['enunciados_nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "datos/enunciados_nuevo/add/",
            'atributos' => 'title="Agregar un nuevo enunciado"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('enunciados', 'enunciados_nuevo');
        $elementos_rol[1] = array('enunciados', 'enunciados_nuevo');
        $elementos_rol[2] = array('enunciados', 'enunciados_nuevo');
        $elementos_rol[3] = array('enunciados', 'enunciados_nuevo');
        $elementos_rol[4] = array('enunciados', 'enunciados_nuevo');
        $elementos_rol[5] = array('enunciados', 'enunciados_nuevo');
        $elementos_rol[8] = array('enunciados', 'enunciados_nuevo');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);