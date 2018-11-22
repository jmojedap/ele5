<?php
    $seccion_sm = $this->uri->segment(2);
    if ( $this->uri->segment(2) == 'importar_e' ) { $seccion_sm = 'importar_inscripciones'; }
    if ( $this->uri->segment(2) == 'importar_inscripciones_e' ) { $seccion_sm = 'importar_inscripciones'; }
    if ( $this->uri->segment(2) == 'importar_certificar_grupos_e' ) { $seccion_sm = 'importar_certificar_grupos'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Grupos',
            'link' => "grupos/importar/",
            'atributos' => 'title="Explorar grupos"'
        );
            
        $arr_menus['importar_inscripciones'] = array(
            'icono' => '<i class="fa fa-check"></i>',
            'texto' => 'Inscripciones',
            'link' => "grupos/importar_inscripciones/",
            'atributos' => 'title="Importar datos de inscripciones"'
        );
        
        $arr_menus['importar_certificar_grupos'] = array(
            'icono' => '<i class="fa fa-check"></i>',
            'texto' => 'Certificar',
            'link' => "grupos/importar_certificar_grupos/",
            'atributos' => 'title="Cargar listado de grupos para certificar sus estudiantes"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('importar', 'importar_inscripciones', 'importar_certificar_grupos');
        $elementos_rol[1] = array('importar', 'importar_inscripciones', 'importar_certificar_grupos');
        $elementos_rol[2] = array('importar', 'importar_inscripciones');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);