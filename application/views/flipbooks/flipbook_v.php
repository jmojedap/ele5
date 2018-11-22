<?php

    //Clases de menús activos e inactivos
        $clases[$this->uri->segment(2)] = 'active';

        if ( $this->uri->segment(2) == 'cargar' ) { $clases['paginas'] = 'active'; }
        if ( $this->uri->segment(2) == 'ver_anotaciones' ) { $clases['anotaciones'] = 'active'; }
        if ( $this->uri->segment(2) == 'importar_programacion' ) { $clases['programar_temas'] = 'active'; }
        if ( $this->uri->segment(2) == 'importar_programacion_e' ) { $clases['programar_temas'] = 'active'; }
    
    //Atributos de los elementos del menú
        
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => '',
            'link' => 'flipbooks/explorar',
            'atributos' => '',
        );
    
        $arr_menus['abrir'] = array(
            'icono' => '<i class="fa fa-external-link"></i>',
            'texto' => 'Abrir',
            'link' => "flipbooks/leer/{$row->id}",
            'atributos' => 'target="_blank"',
        );
    
        $arr_menus['temas'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Temas',
            'link' => "flipbooks/temas/{$row->id}",
            'atributos' => '',
        );
    
        $arr_menus['programar_temas'] = array(
            'icono' => '<i class="fa fa-calendar-o"></i>',
            'texto' => 'Programar',
            'link' => "flipbooks/programar_temas/{$row->id}",
            'atributos' => '',
        );
    
        $arr_menus['paginas'] = array(
            'icono' => '<i class="fa fa-file-o"></i>',
            'texto' => 'Páginas',
            'link' => "flipbooks/paginas/{$row->id}",
            'atributos' => '',
        );
    
        $arr_menus['crear_cuestionario'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionario',
            'link' => "flipbooks/crear_cuestionario/{$row->id}",
            'atributos' => '',
        );
    
        $arr_menus['aperturas'] = array(
            'icono' => '<i class="fa fa-eye"></i>',
            'texto' => 'Lectores',
            'link' => "flipbooks/aperturas/{$row->id}",
            'atributos' => '',
        );
    
        $arr_menus['asignados'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Asignados',
            'link' => "flipbooks/asignados/{$row->id}",
            'atributos' => '',
        );
            
        $arr_menus['anotaciones'] = array(
            'icono' => '<i class="fa fa-sticky-note-o"></i>',
            'texto' => 'Anotaciones',
            'link' => "flipbooks/anotaciones/{$row->id}",
            'atributos' => '',
        );
    
        $arr_menus['copiar'] = array(
            'icono' => '<i class="fa fa-files-o"></i>',
            'texto' => 'Clonar',
            'link' => "flipbooks/copiar/{$row->id}",
            'atributos' => '',
        );
    
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "flipbooks/editar/edit/{$row->id}",
            'atributos' => '',
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'abrir', 'temas', 'programar_temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');
        $elementos_rol[1] = array('explorar', 'abrir', 'temas', 'programar_temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');
        $elementos_rol[2] = array('explorar', 'abrir', 'temas', 'programar_temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');
        
        $elementos_rol[3] = array('crear_cuestionario', 'programar_temas', 'aperturas', 'anotaciones');
        $elementos_rol[4] = array('crear_cuestionario', 'programar_temas', 'aperturas', 'anotaciones');
        $elementos_rol[5] = array('crear_cuestionario', 'programar_temas', 'aperturas', 'anotaciones');
        
        $elementos_rol[6] = array('abrir');
        
        $elementos_rol[7] = array('crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones');
        $elementos_rol[8] = array('explorar', 'abrir', 'temas', 'crear_cuestionario', 'paginas', 'aperturas', 'asignados', 'anotaciones', 'copiar', 'editar');    
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
?>

<div class="div2">
    
    <span class="etiqueta nivel w1"> <?= $row->nivel ?></span>
    <?= $this->App_model->etiqueta_area($row->area_id) ?>
    
    <span class="suave"> | </span>
    
    <span class="suave"><i class="fa fa-calendar-o"></i></span>
    <span class="resaltar"> <?= $row->anio_generacion ?></span>
    <span class="suave"> | </span>

    <span class="suave">Páginas</span>
    <span class="resaltar"> <?= $row->num_paginas ?></span> | 

    

    <?php if (in_array($this->session->userdata('rol_id'), array(0, 1, 2, 8) ) ){ ?>

        <span class="suave">Tipo</span>
        <span class="resaltar"> <?= $this->Item_model->nombre(11, $row->tipo_flipbook_id); ?></span> |

        <?php
            $link_taller = $this->App_model->nombre_flipbook($row->taller_id);
            if ( ! is_null($row->taller_id) ) { $link_taller = anchor("flipbooks/ver_flipbook/{$row->taller_id}", $link_taller, 'target="_blank"'); }
        ?>
        <span class="suave">Taller asociado</span>
        <span class="resaltar"> <?= $link_taller ?></span> |
    <?php } ?>
</div>

<?= $this->load->view('app/menu_v', $data_menu)?>
<?= $this->load->view($vista_b)?>
            
