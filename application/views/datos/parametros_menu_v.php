<?php

    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['sis_opcion'] = array(
            'icono' => '',
            'texto' => 'General',
            'link' => 'datos/sis_opcion',
            'atributos' => ''
        );
        
        $arr_menus['acl_recursos'] = array(
            'icono' => '',
            'texto' => 'ACL',
            'link' => 'develop/acl_recursos',
            'atributos' => ''
        );
        
        $arr_menus['areas'] = array(
            'icono' => '',
            'texto' => 'Áreas',
            'link' => 'datos/areas',
            'atributos' => ''
        );
        
        $arr_menus['competencias'] = array(
            'icono' => '',
            'texto' => 'Competencias',
            'link' => 'datos/competencias',
            'atributos' => ''
        );
        
        $arr_menus['componentes'] = array(
            'icono' => '',
            'texto' => 'Componentes',
            'link' => 'datos/componentes',
            'atributos' => ''
        );
        
        $arr_menus['tipos_recurso'] = array(
            'icono' => '',
            'texto' => 'Tipos recurso',
            'link' => 'datos/tipos_recurso',
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('sis_opcion', 'acl_recursos', 'areas', 'competencias', 'componentes', 'tipos_recurso');
        $elementos_rol[1] = array('sis_opcion', 'acl_recursos', 'areas', 'competencias', 'componentes', 'tipos_recurso');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
    
?>

<?= $this->load->view('app/menu_v', $data_menu)?>