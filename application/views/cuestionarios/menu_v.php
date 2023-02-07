<?php
    
    $editable = $this->Cuestionario_model->editable($row->id);

    //Clases
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cuestionarios_resumen01' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'pregunta_nueva' ) { $seccion = 'preguntas'; }
        if ( $this->uri->segment(2) == 'n_asignar' ) { $seccion = 'asignar'; }

        $clases[$seccion] = 'active';

        $mostrar_actividad = FALSE;

        if ( $this->session->userdata('rol_id') <= 5 ) { $mostrar_actividad = TRUE; }
        if ( $row->id == $this->session->userdata('usuario_id') ) { $mostrar_actividad = TRUE; }    //Si es él mismo
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '',
            'texto' => 'Explorar',
            'link' => "cuestionarios/explorar/",
            'atributos' => 'title="Ir a lista de cuestionarios"'
        );
            
        $arr_menus['info'] = array(
            'icono' => '',
            'texto' => 'Información',
            'link' => "cuestionarios/info/{$row->id}",
            'atributos' => 'title="Información general sobre el cuestionario"'
        );

        $arr_menus['vista_previa'] = array(
            'icono' => '',
            'texto' => 'Vista previa',
            'link' => "cuestionarios/vista_previa/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['asignar'] = array(
            'icono' => '',
            'texto' => 'Asignar',
            'link' => "cuestionarios/asignar/{$row->id}",
            'atributos' => 'title="Asignar cuestionario a estudiantes"'
        );
        
        $arr_menus['preguntas'] = array(
            'icono' => '',
            'texto' => 'Preguntas',
            'link' => "cuestionarios/preguntas/{$row->id}",
            'atributos' => 'title="Preguntas del cuestionario"'
        );
        
        $arr_menus['temas'] = array(
            'icono' => '',
            'texto' => 'Temas',
            'link' => "cuestionarios/temas/{$row->id}",
            'atributos' => 'title="Temas de las pregutnas"'
        );
        
        $arr_menus['grupos'] = array(
            'icono' => '',
            'texto' => 'Grupos',
            'link' => "cuestionarios/grupos/{$row->id}",
            'atributos' => 'title="Grupos en los que está asignado el cuestionario"'
        );
        
        $arr_menus['copiar_cuestionario'] = array(
            'icono' => '',
            'texto' => 'Copia',
            'link' => "cuestionarios/copiar_cuestionario/{$row->id}",
            'atributos' => 'title="Crear copia de cuestionario"'
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil-alt"></i>',
            'texto' => 'Editar',
            'link' => "cuestionarios/editar/edit/{$row->id}",
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'info', 'vista_previa', 'preguntas', 'temas', 'grupos', 'asignar', 'copiar_cuestionario', 'editar');
        $elementos_rol[1] = array('explorar', 'info', 'vista_previa', 'preguntas', 'temas', 'grupos', 'asignar', 'copiar_cuestionario', 'editar');
        $elementos_rol[2] = array('explorar', 'info', 'vista_previa', 'preguntas', 'temas', 'grupos', 'asignar', 'copiar_cuestionario', 'editar');
        $elementos_rol[3] = array('explorar', 'vista_previa', 'temas', 'grupos', 'asignar');
        $elementos_rol[4] = array('explorar', 'vista_previa', 'temas', 'grupos', 'asignar');
        $elementos_rol[5] = array('explorar', 'vista_previa', 'temas', 'grupos', 'asignar');
        $elementos_rol[7] = array('explorar', 'vista_previa', 'preguntas');
        
    //Si es editable
        if ( $editable ) {
            $elementos_rol[3] = array('explorar', 'vista_previa', 'preguntas', 'grupos', 'asignar', 'editar');
            $elementos_rol[4] = array('explorar', 'vista_previa', 'preguntas', 'grupos', 'asignar', 'editar');
            $elementos_rol[5] = array('explorar', 'vista_previa', 'preguntas', 'grupos', 'asignar', 'editar');
        }
        
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
?>

<?php $this->load->view('comunes/bs4/menu_v', $data_menu)?>