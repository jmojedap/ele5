<div class="sep2">
    <p>
        <span class="suave"><i class="fa fa-calendar-o" title="Año generación"></i></span>
        <span class="resaltar"><?= $row->anio_generacion ?></span>
        <span class="suave"> | </span>

        <span class="suave"><i class="fa fa-bank"></i></span>
        <span class="resaltar"> <?= $this->App_model->nombre_institucion($row->institucion_id, 1) ?></span>
        <span class="suave"> | </span>

        <span class="suave">Estudiantes:</span>
        <span class="resaltar"> <?= $row->num_estudiantes ?></span>
        <span class="suave"> | </span>
    </p>
    
</div>

<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cargar_estudiantes' ) { $seccion = 'estudiantes'; }
        if ( $this->uri->segment(2) == 'editar_estudiantes' ) { $seccion = 'estudiantes'; }
        if ( $this->uri->segment(2) == 'asignar_flipbook' ) { $seccion = 'anotaciones'; }
        if ( $this->uri->segment(2) == 'quitar_flipbook' ) { $seccion = 'anotaciones'; }
        
        if ( $this->uri->segment(2) == 'cuestionarios_resumen01' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen03' ) { $seccion = 'cuestionarios'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['grupos'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => '',
            'link' => "instituciones/grupos/{$row->institucion_id}",
            'atributos' => 'title="Explorar grupos"'
        );
            
        $arr_menus['estudiantes'] = array(
            'icono' => '',
            'texto' => 'Estudiantes',
            'link' => "grupos/estudiantes/{$row->id}",
            'atributos' => 'title="Estudiantes del grupo"'
        );
            
        $arr_menus['anotaciones'] = array(
            'icono' => '',
            'texto' => 'Anotaciones',
            'link' => "grupos/anotaciones/{$row->id}",
            'atributos' => 'title="Contenidos asignados a estudiantes del grupo"'
        );
            
        $arr_menus['quices'] = array(
            'icono' => '',
            'texto' => 'Evidencias aprendizaje',
            'link' => "grupos/quices/{$row->id}",
            'atributos' => 'title="Evidencias de aprendizaje"'
        );
            
        $arr_menus['cuestionarios_flipbooks'] = array(
            'icono' => '',
            'texto' => 'Crear cuestionarios',
            'link' => "grupos/cuestionarios_flipbooks/{$row->id}",
            'atributos' => 'title="Crear cuestionarios desde Contenidos"'
        );
            
        $arr_menus['cuestionarios'] = array(
            'icono' => '',
            'texto' => 'Resultados desempeño',
            'link' => "grupos/cuestionarios/{$row->id}",
            'atributos' => 'title="Resultados de los cuestionarios"'
        );
            
        $arr_menus['profesores'] = array(
            'icono' => '',
            'texto' => 'Profesores',
            'link' => "grupos/profesores/{$row->id}",
            'atributos' => 'title="Profesores del grupo"'
        );
            
        $arr_menus['promover'] = array(
            'icono' => '<i class="fa fa-check-square-o"></i>',
            'texto' => 'Promover',
            'link' => "grupos/promover/{$row->id}/1",
            'atributos' => 'title="Profesores del grupo"'
        );
            
        $arr_menus['mensaje'] = array(
            'icono' => '<i class="fa fa-comment-o"></i>',
            'texto' => 'Enviar mensaje',
            'link' => "mensajes/nuevo_grupal/{$row->id}",
            'atributos' => 'title="Enviar mensaje a los estudiantes del grupo"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "grupos/editar/edit/{$row->id}",
            'atributos' => 'title="Editar grupo"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('grupos', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'promover', 'mensaje', 'editar');
        $elementos_rol[1] = array('grupos', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'promover', 'mensaje', 'editar');
        $elementos_rol[2] = array('grupos', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'promover', 'mensaje', 'editar');
        $elementos_rol[3] = array('grupos', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'promover', 'mensaje', 'editar');
        $elementos_rol[4] = array('grupos', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'mensaje');
        $elementos_rol[5] = array('grupos', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'mensaje');
        $elementos_rol[8] = array('grupos', 'estudiantes', 'anotaciones', 'quices', 'cuestionarios_flipbooks', 'cuestionarios', 'profesores', 'mensaje', 'editar');
        
        
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


            
