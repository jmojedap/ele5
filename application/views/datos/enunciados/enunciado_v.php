<p>
    <span class="text-muted">Creado</span>
    <span class="text-primary"><?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?></span>
</p>

<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['enunciados'] = array(
            'icono' => '<i class="fa fa-arrow-left"></i>',
            'texto' => 'Explorar',
            'link' => "enunciados/explorar/",
            'atributos' => 'title="Explorar enunciados"'
        );
            
        $arr_menus['ver'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => 'Ver',
            'link' => "enunciados/ver/{$row->id}",
            'atributos' => 'title="Ver enunciados"'
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "enunciados/editar/edit/{$row->id}",
            'atributos' => 'title="Editar enunciados"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('enunciados', 'ver', 'editar');
        $elementos_rol[1] = array('enunciados', 'ver', 'editar');
        $elementos_rol[2] = array('enunciados', 'ver', 'editar');
        $elementos_rol[3] = array('enunciados', 'ver', 'editar');
        $elementos_rol[4] = array('enunciados', 'ver', 'editar');
        $elementos_rol[5] = array('enunciados', 'ver', 'editar');
        $elementos_rol[8] = array('enunciados', 'ver', 'editar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);