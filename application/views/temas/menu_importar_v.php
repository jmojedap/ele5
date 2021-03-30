<?php
    //Submenú
        $seccion = $this->uri->segment(2);
        if ( $seccion == 'importar_ut_e' ) { $clases_sm['importar_ut'] = 'active'; }
        if ( $seccion == 'copiar_preguntas_e' ) { $clases_sm['copiar_preguntas'] = 'active'; }
        if ( $seccion == 'asignar_quices_e' ) { $clases_sm['asignar_quices'] = 'active'; }
        if ( $seccion == 'importar_pa_e' ) { $clases_sm['importar_pa'] = 'active'; }
        if ( $seccion == 'importar_lecturas_dinamicas_e' ) { $clases_sm['importar_lecturas_dinamicas'] = 'active'; }
        if ( $seccion == 'eliminar_preguntas_abiertas_e' ) { $clases_sm['eliminar_preguntas_abiertas'] = 'active'; }

        $clases_sm[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['importar'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Temas',
            'link' => "temas/importar/",
            'atributos' => 'title="Importar temas desde MS Excel"'
        );
            
        $arr_menus['importar_ut'] = array(
            'icono' => '<i class="fa fa-sitemap"></i>',
            'texto' => 'Elementos UT',
            'link' => "temas/importar_ut/",
            'atributos' => 'title="Importar elementos de unidades temáticas"'
        );
        
        $arr_menus['copiar_preguntas'] = array(
            'icono' => '<i class="fa fa-clone"></i>',
            'texto' => 'Copiar preguntas',
            'link' => 'temas/copiar_preguntas/',
            'atributos' => 'title="Copiar preguntas de un tema a otro, formato Excel"'
        );
        
        $arr_menus['asignar_quices'] = array(
            'icono' => '<i class="fa fa-angle-double-right"></i>',
            'texto' => 'Asignar evidencias',
            'link' => 'temas/asignar_quices/',
            'atributos' => 'title="Asingar las evidencias de un tema a otro"'
        );

        $arr_menus['importar_pa'] = array(
            'icono' => '<i class="fas fa-feather-alt"></i>',
            'texto' => 'Preguntas abiertas',
            'link' => 'temas/importar_pa/',
            'atributos' => 'title="Importar preguntas abiertas a los temas"'
        );

        $arr_menus['importar_lecturas_dinamicas'] = array(
            'icono' => '<i class="far fa-file-alt"></i>',
            'texto' => 'Lecturas dinámicas',
            'link' => 'temas/importar_lecturas_dinamicas/',
            'atributos' => 'title="Importar lecturas dinámicas a los temas"'
        );

        $arr_menus['eliminar_preguntas_abiertas'] = array(
            'icono' => '<i class="fa fa-trash"></i>',
            'texto' => 'Preguntas abiertas',
            'link' => 'temas/eliminar_preguntas_abiertas/',
            'atributos' => 'title="Eliminar preguntas abiertas"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('importar', 'importar_ut', 'copiar_preguntas', 'asignar_quices', 'importar_pa', 'importar_lecturas_dinamicas', 'eliminar_preguntas_abiertas');
        $elementos_rol[1] = array('importar', 'importar_ut', 'copiar_preguntas', 'asignar_quices', 'importar_pa', 'importar_lecturas_dinamicas', 'eliminar_preguntas_abiertas');
        $elementos_rol[2] = array('importar', 'importar_ut');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases_sm'] = $clases_sm;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion_sm'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/submenu_v', $data_menu);