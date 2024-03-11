<?php
    $seccion = $this->uri->segment(2);
    //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

    $clases[$seccion] = 'active';

//Atributos de los elementos del menú
    $arr_menus['explorar'] = array(
        'icono' => '<i class="fa fa-arrow-left"></i>',
        'texto' => '',
        'link' => "quices/explorar/",
        'atributos' => 'title="Explorar evidencias"'
    );
    
    $arr_menus['temas'] = array(
        'icono' => '',
        'texto' => 'Temas',
        'link' => "quices/temas/{$row->id}",
        'atributos' => 'title="Temas asociados a la evidencia"'
    );
        
    $arr_menus['construir'] = array(
        'icono' => '',
        'texto' => 'Construir',
        'link' => "quices/construir/{$row->id}",
        'atributos' => 'title="Explorar evidencias"'
    );
        
    $arr_menus['elementos'] = array(
        'icono' => '',
        'texto' => 'Elementos',
        'link' => "quices/elementos/{$row->id}",
        'atributos' => 'title="Elementos de la evidencia"'
    );

    $arr_menus['images'] = array(
        'icono' => '',
        'texto' => 'Imágenes',
        'link' => "quices/images/{$row->id}",
        'atributos' => 'title="Imágenes de la evidencia"'
    );
        
    $arr_menus['detalle'] = array(
        'icono' => '',
        'texto' => 'Detalle',
        'link' => "quices/detalle/{$row->id}",
        'atributos' => 'title="Detalle de los elementos de la evidencia"'
    );
        
    $arr_menus['editar'] = array(
        'icono' => '',
        'texto' => 'Editar',
        'link' => "quices/editar/{$row->id}",
        'atributos' => 'title="Editar evidencia"'
    );
    
//Elementos de menú según el rol del visitante
    $elementos_rol[0] = array('explorar', 'temas', 'construir', 'images', 'editar', 'elementos', 'detalle');
    $elementos_rol[1] = array('explorar', 'temas', 'construir', 'images', 'editar',);
    $elementos_rol[2] = array('explorar', 'temas', 'construir', 'images', 'editar',);
    $elementos_rol[9] = array('explorar', 'temas', 'construir', 'images', 'editar',);

    
//Definiendo menú mostrar, según el rol del visitante
    $elementos = $elementos_rol[$this->session->userdata('rol_id')];
    
//Modificar menús según el tipo
    if ( $row->tipo_quiz_id > 200 ) {unset($elementos[2]);};
    if ( $row->tipo_quiz_id < 200 ) {unset($elementos[3]);};
    

//Array data para la vista: comunes/menu_v
    $data_menu['elementos'] = $elementos;
    $data_menu['clases'] = $clases;
    $data_menu['arr_menus'] = $arr_menus;
    $data_menu['seccion'] = $seccion;

//Cargue vista
    $this->load->view('comunes/bs4/menu_v', $data_menu);