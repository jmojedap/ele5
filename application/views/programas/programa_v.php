<p>
    <span class="resaltar"> <?= $this->App_model->etiqueta_area($row->area_id) ?></span>
    <span class="etiqueta nivel w1"><?= $row->nivel ?></span> |

    <span class="suave">Año:</span>
    <span class="resaltar"> <?= $row->anio_generacion ?></span> |

    <span class="suave">Institución:</span>
    <span class="resaltar"> <?= $this->App_model->nombre_institucion($row->institucion_id) ?></span> |

    <span class="suave">Creado por:</span>
    <span class="resaltar"> <?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?></span> |

    <span class="suave">Creado:</span>
    <span class="resaltar"> <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d h:i a') ?></span> |
</p>
<p><?= $row->descripcion ?></p>

<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'cargar_temas' ) { $seccion = 'temas'; }
        if ( $this->uri->segment(2) == 'procesar_cargue' ) { $seccion = 'temas'; }
        if ( $this->uri->segment(2) == 'generar_flipbook' ) { $seccion = 'nuevo_flipbook'; }
        if ( $this->uri->segment(2) == 'generar_cuestionario' ) { $seccion = 'nuevo_cuestionario'; }
        if ( $this->uri->segment(2) == 'copiar_e' ) { $seccion = 'copiar'; }
        if ( $this->uri->segment(2) == 'editar_temas' ) { $seccion = 'editar'; }

        $clases[$seccion] = 'active';if ( $this->uri->segment(2) == 'copiar_e' ) { $seccion = 'copiar'; }
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-caret-left"></i>',
            'texto' => 'Programas',
            'link' => "programas/explorar/",
            'atributos' => 'title="Explorar programas"'
        );
            
        $arr_menus['temas'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Temas',
            'link' => "programas/temas/{$row->id}",
            'atributos' => 'title="Temas del programa"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "programas/editar_temas/edit/{$row->id}",
            'atributos' => 'title="Editar programa"'
        );
        
        $arr_menus['flipbooks'] = array(
            'icono' => '<i class="fa fa-book"></i>',
            'texto' => 'Contenidos',
            'link' => "programas/flipbooks/{$row->id}",
            'atributos' => 'title="Contenidos generados con el programa"'
        );
        
        $arr_menus['nuevo_flipbook'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Contenido',
            'link' => "programas/nuevo_flipbook/{$row->id}",
            'atributos' => 'title="Generar un nuevo contenido desde el programa"'
        );
            
        $arr_menus['nuevo_cuestionario'] = array(
            'icono' => '<i class="fa fa-plus"></i>',
            'texto' => 'Cuestionario',
            'link' => "programas/nuevo_cuestionario/{$row->id}",
            'atributos' => 'title="Generar un nuevo cuestionario desde el programa"'
        );
            
        $arr_menus['copiar'] = array(
            'icono' => '<i class="fa fa-copy"></i>',
            'texto' => 'Copiar',
            'link' => "programas/copiar/{$row->id}",
            'atributos' => 'title="Crear copia del programa"'
        );
            
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'temas', 'editar', 'nuevo_flipbook', 'nuevo_cuestionario', 'copiar');
        $elementos_rol[1] = array('explorar', 'temas', 'editar', 'nuevo_flipbook', 'nuevo_cuestionario', 'copiar');
        $elementos_rol[2] = array('explorar', 'temas', 'editar', 'nuevo_flipbook', 'nuevo_cuestionario', 'copiar');
        $elementos_rol[3] = array('explorar', 'temas', 'editar');
        $elementos_rol[4] = array('explorar', 'temas', 'editar');
        $elementos_rol[8] = array('explorar', 'temas', 'editar', 'copiar');
        
        
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