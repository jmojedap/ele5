<?php
    
        $nombre_institucion = $this->App_model->nombre_institucion($row->institucion_id, 1);

        $nombre_grupo = 'NA';
        if ( $row->rol_id == 6 ) { $nombre_grupo = $this->App_model->nombre_grupo($row->grupo_id, 1); }

        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cuestionarios_resumen01' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_area' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_detalle' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_competencias' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_componentes' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen03' ) { $seccion = 'cuestionarios'; }

        $clases[$seccion] = 'active';

        $mostrar_actividad = FALSE;

        if ( $this->session->userdata('rol_id') <= 5 ) { $mostrar_actividad = TRUE; }
        if ( $row->id == $this->session->userdata('usuario_id') ) { $mostrar_actividad = TRUE; }    //Si es él mismo
    
    //Atributos de los elementos del menú
        $arr_menus['grupo'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => '',
            'link' => "grupos/estudiantes/{$row->grupo_id}",
            'atributos' => 'title="Ir al grupo de estudiantes"'
        );
            
        $arr_menus['actividad'] = array(
            'icono' => '<i class="fa fa-th-list"></i>',
            'texto' => 'Actividad',
            'link' => "usuarios/actividad/{$usuario_id}",
            'atributos' => ''
        );
        
        $arr_menus['anotaciones'] = array(
            'icono' => '<i class="fa fa-sticky-note-o"></i>',
            'texto' => 'Anotaciones',
            'link' => "usuarios/anotaciones/{$usuario_id}",
            'atributos' => ''
        );
        
        $arr_menus['flipbooks'] = array(
            'icono' => '<i class="fa fa-book"></i>',
            'texto' => 'Contenidos',
            'link' => "usuarios/flipbooks/{$usuario_id}",
            'atributos' => ''
        );
        
        $arr_menus['quices'] = array(
            'icono' => '<i class="fa fa-cube"></i>',
            'texto' => 'Evidencias de aprendizaje',
            'link' => "usuarios/quices/{$usuario_id}",
            'atributos' => ''
        );
        
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionarios',
            'link' => "usuarios/cuestionarios/{$usuario_id}",
            'atributos' => ''
        );
        
        $arr_menus['grupos'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Grupos',
            'link' => "usuarios/grupos/{$usuario_id}",
            'atributos' => ''
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editar/edit/{$usuario_id}",
            'atributos' => ''
        );
            
        $arr_menus['master_login'] = array(
            'icono' => '<i class="fa fa-sign-in"></i>',
            'texto' => 'Acceder',
            'link' => "develop/ml/{$usuario_id}",
            'atributos' => 'title="Ingresar como este usuario"'
        );
            
        $arr_menus['contrasena'] = array(
            'icono' => '<i class="fa fa-lock"></i>',
            'texto' => 'Contraseña',
            'link' => "usuarios/contrasena",
            'atributos' => 'title="Cambio de contraseña de usuario"'
        );
        
        $arr_menus['editarme'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editarme/edit/{$usuario_id}",
            'atributos' => ''
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('grupo', 'actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios', 'grupos', 'editar', 'master_login');
        $elementos_rol[1] = array('grupo', 'actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios', 'grupos', 'editar', 'master_login');
        $elementos_rol[2] = array('grupo', 'actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios', 'grupos', 'editar');
        $elementos_rol[3] = array('actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios', 'grupos');
        $elementos_rol[4] = array('actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios', 'grupos');
        $elementos_rol[5] = array('actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios');
        $elementos_rol[6] = array('actividad');
        $elementos_rol[7] = array('actividad');
        $elementos_rol[8] = array('grupo', 'actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios', 'grupos');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Estudiante visita perfil de otro estudiante
        if ( $row->id == $this->session->userdata('usuario_id') && $this->session->userdata('rol_id') == 6 )
        {
            $elementos = array('actividad', 'anotaciones', 'flipbooks', 'quices', 'cuestionarios');
        }
        
    //Elementos especiales
        if ( $editable && $row->id == $this->session->userdata('usuario_id') )
        {
            $elementos[] = 'editarme';
        }
        
        if ( $row->id == $this->session->userdata('usuario_id') ) {
            $elementos[] = 'contrasena';
        }
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
    
?>
    
<p class="hidden-xs">
    <span class="resaltar"><?= $this->App_model->nombre_item($row->rol_id, 1, 6); ?></span> | 
    <span class="suave">Username:</span>
    <span class="resaltar"><?= $row->username ?></span> | 
    <span class="suave"><i class="fa fa-bank"></i></span>
    <span class="resaltar"><?= $nombre_institucion ?></span> | 
    <span class="suave">Grupo actual:</span>
    <span class="resaltar"><?= $nombre_grupo ?></span> | 
</p>

<?php $this->load->view('app/menu_v', $data_menu)?>
<?php $this->load->view($vista_b)?>