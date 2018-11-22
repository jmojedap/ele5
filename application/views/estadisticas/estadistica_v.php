<?php $this->load->view('assets/highcharts'); ?>
<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'login_usuarios_ciudad' ) { $seccion = 'login'; }
        if ( $this->uri->segment(2) == 'login_usuarios' ) { $seccion = 'login'; }
        if ( $this->uri->segment(2) == 'login_diario' ) { $seccion = 'login'; }
        if ( $this->uri->segment(2) == 'login_nivel' ) { $seccion = 'login'; }
        if ( $this->uri->segment(2) == 'login_instituciones' ) { $seccion = 'login'; }
        
        if ( $this->uri->segment(2) == 'flipbooks_nivel' ) { $seccion = 'contenidos'; }
        if ( $this->uri->segment(2) == 'flipbooks_area' ) { $seccion = 'contenidos'; }
        
        if ( $this->uri->segment(2) == 'quices_nivel' ) { $seccion = 'quices'; }
        if ( $this->uri->segment(2) == 'quices_area' ) { $seccion = 'quices'; }
        
        if ( $this->uri->segment(2) == 'respuesta_cuestionarios' ) { $seccion = 'cuestionarios'; }
        

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['login'] = array(
            'icono' => '<i class="fa fa-sign-in"></i>',
            'texto' => 'Login',
            'link' => "estadisticas/login_diario/",
            'atributos' => 'title="Login diario de estadisticas"'
        );
            
        $arr_menus['contenidos'] = array(
            'icono' => '<i class="fa fa-book"></i>',
            'texto' => 'Contenidos',
            'link' => "estadisticas/flipbooks_nivel/",
            'atributos' => 'title="Apertura de contenidos por día"'
        );
        
        $arr_menus['quices'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Evidencias',
            'link' => "estadisticas/quices_area/",
            'atributos' => 'title="Respuesta evidencias por área"'
        );
        
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionarios',
            'link' => "estadisticas/respuesta_cuestionarios/",
            'atributos' => 'title="Respuesta de cuestionarios por día"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('login', 'contenidos', 'cuestionarios', 'quices');
        $elementos_rol[1] = array('login', 'contenidos', 'cuestionarios', 'quices');
        $elementos_rol[2] = array('login', 'contenidos', 'quices');
        $elementos_rol[3] = array('login', 'contenidos', 'quices');
        $elementos_rol[4] = array('login', 'contenidos', 'quices');
        
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
    




