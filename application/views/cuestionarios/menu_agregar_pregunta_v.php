<?php
        $seccion_sm = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases_sm[$seccion_sm] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['preguntas'] = array(
            'icono' => '<i class="fa fa-caret-left"></i>',
            'texto' => 'Preguntas',
            'link' => "cuestionarios/preguntas/{$row->id}",
            'atributos' => 'title="Volver a preguntas"'
        );
            
        $arr_menus['pregunta_nueva'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nueva',
            'link' => "cuestionarios/pregunta_nueva/{$row->id}/{$orden}/add",
            'atributos' => 'title="Crear una nueva pregunta"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('preguntas', 'pregunta_nueva');
        $elementos_rol[1] = array('preguntas', 'pregunta_nueva');
        $elementos_rol[2] = array('preguntas', 'pregunta_nueva');
        $elementos_rol[3] = array('preguntas', 'pregunta_nueva');
        $elementos_rol[4] = array('preguntas', 'pregunta_nueva');
        $elementos_rol[5] = array('preguntas', 'pregunta_nueva');
        
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion_sm;
    
    //Cargue vista
        $this->load->view('comunes/submenu_v', $data_menu);
