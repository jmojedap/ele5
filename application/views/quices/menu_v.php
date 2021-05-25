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
        'icono' => '<i class="fa fa-bars"></i>',
        'texto' => 'Temas',
        'link' => "quices/temas/{$row->id}",
        'atributos' => 'title="Temas asociados a la evidencia"'
    );
        
    $arr_menus['construir'] = array(
        'icono' => '<i class="fa fa-wrench"></i>',
        'texto' => 'Construir',
        'link' => "quices/construir/{$row->id}",
        'atributos' => 'title="Explorar evidencias"'
    );
    
    $arr_menus['resolver'] = array(
        'icono' => '<i class="fas fa-external-link-alt"></i>',
        'texto' => 'Vista previa',
        'link' => "quices/resolver/{$row->id}",
        'atributos' => 'title="Vista previa de la Evidencia" target="_blank"'
    );
        
    $arr_menus['elementos'] = array(
        'icono' => '<i class="fa fa-list"></i>',
        'texto' => 'Elementos',
        'link' => "quices/elementos/{$row->id}",
        'atributos' => 'title="Elementos de la evidencia"'
    );
        
    $arr_menus['detalle'] = array(
        'icono' => '<i class="fa fa-book"></i>',
        'texto' => 'Detalle',
        'link' => "quices/detalle/{$row->id}",
        'atributos' => 'title="Detalle de los elementos de la evidencia"'
    );
        
    $arr_menus['editar'] = array(
        'icono' => '<i class="fa fa-pencil-alt"></i>',
        'texto' => 'Editar',
        'link' => "quices/editar/{$row->id}",
        'atributos' => 'title="Editar evidencia"'
    );
    
//Elementos de menú según el rol del visitante
    $elementos_rol[0] = array('explorar', 'temas', 'construir', 'editar', 'resolver', 'elementos', 'detalle');
    $elementos_rol[1] = array('explorar', 'temas', 'construir', 'editar', 'resolver');
    $elementos_rol[2] = array('explorar', 'temas', 'construir', 'editar', 'resolver');
    
//Definiendo menú mostrar, según el rol del visitante
    $elementos = $elementos_rol[$this->session->userdata('rol_id')];
    
//Array data para la vista: comunes/menu_v
    $data_menu['elementos'] = $elementos;
    $data_menu['clases'] = $clases;
    $data_menu['arr_menus'] = $arr_menus;
    $data_menu['seccion'] = $seccion;

//Cargue vista
    $this->load->view('comunes/bs4/menu_v', $data_menu);