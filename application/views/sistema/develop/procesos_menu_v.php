<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['procesos'] = array(
            'icono' => '',
            'texto' => 'Procesos',
            'link' => "develop/procesos/",
            'atributos' => 'title="Explorar usuarios"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('procesos');
        $elementos_rol[1] = array('procesos');
        $elementos_rol[2] = array('procesos');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);

?>
