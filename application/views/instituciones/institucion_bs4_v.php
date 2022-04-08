<?php
    
    /*$cant_login = $this->Institucion_model->cant_login($row->id);
    $cant_estudiantes = $this->Institucion_model->cant_estudiantes($row->id);
    $cant_pagaron = $this->Institucion_model->cant_estudiantes($row->id, 'pago = 1');
    
    $porcentaje_pagaron = 0; 
    if ( $cant_estudiantes > 0 ) { $porcentaje_pagaron = 100 * $cant_pagaron / $cant_estudiantes; }

    $promedio_login = 0;
    if ( $cant_login > 0 ) { $promedio_login = $cant_login / $cant_estudiantes; } */

    $cant_login = 0;
    $cant_estudiantes = $this->Institucion_model->cant_estudiantes($row->id);
    $cant_pagaron = $this->Institucion_model->cant_estudiantes($row->id, 'pago = 1');
    
    $porcentaje_pagaron = 0; 
    if ( $cant_estudiantes > 0 ) { $porcentaje_pagaron = 100 * $cant_pagaron / $cant_estudiantes; }

    $promedio_login = 0;
    //if ( $cant_login > 0 ) { $promedio_login = $cant_login / $cant_estudiantes; } 
?>

<div class="sep1">
    <p>
        <span class="suave"><i class="fa fa-users"></i> Cód</span>
        <span class="resaltar"><?= $row->cod ?></span>
        <span class="suave"> | </span>

        <span class="resaltar"><?= $row->lugar_nombre ?></span>
        <span class="suave"> | </span>
        
        <span class="suave"><i class="fa fa-users"></i> Estudiantes</span>
        <span class="resaltar"><?= $cant_estudiantes ?></span>
        <span class="suave"> | </span>
        

        <!-- <span class="suave">Login</span>
        <span class="resaltar"><?= $cant_login ?></span>
        <span class="suave"> | </span> -->

        <!-- <span class="suave">Promedio login</span>
        <span class="resaltar"><?= number_format($promedio_login, 1) ?></span>
        <span class="suave"> | </span> -->
        
        <?php if ( in_array($this->session->userdata('rol_id'), array(0,1,2,8)) ) : ?>                
            <span class="suave">Pagaron</span>
            <span class="resaltar"><?= $cant_pagaron ?></span>
            <span class="suave">(<?= number_format($porcentaje_pagaron, 0) ?>%)</span>
            <span class="suave"> | </span>
        <?php endif ?>
            
        <?php if ( strlen($row->direccion) > 0 ){ ?>
            <span class="suave"><i class="fa fa-map-marker"></i></span>
            <span class="resaltar"> <?= $row->direccion ?></span>
            <span class="suave"> | </span>
        <?php } ?>
        
        <?php if ( strlen($row->telefono) > 0 ){ ?>
            <span class="suave"><i class="fa fa-phone"></i></span>
            <span class="resaltar"> <?= $row->telefono ?></span>
            <span class="suave"> | </span>
        <?php } ?>
    </p>
</div>

<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'nuevo_grupo' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'cargar_grupos' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'cargar_grupos_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_estudiantes' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_estudiantes_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'asignar_profesores' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'asignar_profesores_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'vaciar_grupos' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'vaciar_grupos_e' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_usuarios' ) { $seccion = 'usuarios'; }
        if ( $this->uri->segment(2) == 'importar_usuarios_e' ) { $seccion = 'usuarios'; }
        
        if ( $this->uri->segment(2) == 'importar_grupos' ) { $seccion = 'grupos'; }
        if ( $this->uri->segment(2) == 'importar_grupos_e' ) { $seccion = 'grupos'; }
        
        if ( $this->uri->segment(2) == 'resctn_grupo' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen01' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen03' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'cuestionarios_resumen03' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_grupo' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_area' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_competencia' ) { $seccion = 'cuestionarios'; }
        if ( $this->uri->segment(2) == 'resultados_componente' ) { $seccion = 'cuestionarios'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "instituciones/explorar/",
            'atributos' => 'title="Explorar instituciones"'
        );
            
        $arr_menus['grupos'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Grupos',
            'link' => "instituciones/grupos/{$row->id}",
            'atributos' => 'title="Grupos de la institución"'
        );
        
        $arr_menus['usuarios'] = array(
            'icono' => '<i class="fa fa-user"></i>',
            'texto' => 'Usuarios',
            'link' => "instituciones/usuarios/{$row->id}",
            'atributos' => 'title="Usuarios institucionales"'
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "instituciones/editar/edit/{$row->id}",
            'atributos' => 'title="Editar usuario"'
        );
        
        $arr_menus['flipbooks'] = array(
            'icono' => '<i class="fa fa-book"></i>',
            'texto' => 'Contenidos',
            'link' => "instituciones/flipbooks/{$row->id}",
            'atributos' => 'title="Contenidos asignados a estudiantes de la institución"'
        );
        
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionarios',
            'link' => "instituciones/cuestionarios/{$row->id}",
            'atributos' => 'title="Cuestionarios asignados a estudiantes de la institución"'
        );
        
        $arr_menus['procesos'] = array(
            'icono' => '<i class="fa fa-gear"></i>',
            'texto' => 'Procesos',
            'link' => "instituciones/procesos/{$row->id}",
            'atributos' => 'title="Procesos sobre la institución"'
        );
        
        $arr_menus['mensajes_masivos'] = array(
            'icono' => '<i class="fa fa-comment-o"></i>',
            'texto' => 'Mensajes...',
            'link' => "instituciones/mensajes_masivos/{$row->id}",
            'atributos' => 'title="Mensajes masivos para usuarios de la institución"'
        );
        
        $arr_menus['eliminar_pre'] = array(
            'icono' => '<i class="fa fa-trash"></i>',
            'texto' => 'Eliminar...',
            'link' => "instituciones/eliminar_pre/{$row->id}",
            'atributos' => 'title="Eliminar institución"'
        );
        
        $arr_menus['nuevo'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Nuevo',
            'link' => "instituciones/nuevo/add/",
            'atributos' => 'title="Agregar un nuevo usuario"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'procesos', 'mensajes_masivos', 'editar', 'eliminar_pre');
        $elementos_rol[1] = array('explorar', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'procesos', 'mensajes_masivos', 'editar', 'eliminar_pre');
        $elementos_rol[2] = array('explorar', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'procesos', 'editar');
        $elementos_rol[3] = array('grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'mensajes_masivos');
        $elementos_rol[4] = array('grupos', 'usuarios', 'flipbooks', 'cuestionarios', 'mensajes_masivos');
        $elementos_rol[5] = array('grupos');
        $elementos_rol[8] = array('explorar', 'grupos', 'usuarios', 'flipbooks', 'cuestionarios');
        
    //Definiendo menú mostrar, según el rol del visitante
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: comunes/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
    //Cargue vista
        $this->load->view('comunes/bs4/menu_v', $data_menu);