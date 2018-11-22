<p>
    <span class="suave">Creado</span>
    <span class="resaltar"><?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d') ?></span>
</p>

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
            
        $arr_menus['enunciados_ver'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => 'Ver',
            'link' => "datos/enunciados_ver/{$row->id}",
            'atributos' => 'title="Ver enunciados"'
        );
        
        $arr_menus['enunciados_editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "datos/enunciados_editar/edit/{$row->id}",
            'atributos' => 'title="Editar enunciados"'
        );
        
        
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('enunciados', 'enunciados_ver', 'enunciados_editar');
        $elementos_rol[1] = array('enunciados', 'enunciados_ver', 'enunciados_editar');
        $elementos_rol[2] = array('enunciados', 'enunciados_ver', 'enunciados_editar');
        $elementos_rol[3] = array('enunciados', 'enunciados_ver', 'enunciados_editar');
        $elementos_rol[4] = array('enunciados', 'enunciados_ver', 'enunciados_editar');
        $elementos_rol[5] = array('enunciados', 'enunciados_ver', 'enunciados_editar');
        $elementos_rol[8] = array('enunciados', 'enunciados_ver', 'enunciados_editar');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/menu_v', $data_menu);
        $this->load->view($vista_b);