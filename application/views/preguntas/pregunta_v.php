<p>
    <span class="resaltar"> <?php echo $this->App_model->etiqueta_area($row->area_id) ?></span>
    <span class="resaltar"><span class="etiqueta nivel w1"><?php echo $row->nivel ?></span></span> |
    <span class="suave">Código:</span>
    <span class="resaltar"> <?php echo $row->cod_pregunta ?></span> |
    <span class="suave">Tema:</span>
    <span class="resaltar"> <?php echo anchor("temas/preguntas/{$row->tema_id}", $this->App_model->nombre_tema($row->tema_id)) ?></span> |
    <span class="suave">Componente:</span>
    <span class="resaltar"> <?php echo $this->App_model->nombre_item($row->componente_id) ?></span> |
    <span class="suave">Competencia:</span>
    <span class="resaltar"> <?php echo $this->App_model->nombre_item($row->competencia_id) ?></span> |
    <span class="suave">Proceso de pensamiento:</span>
    <span class="resaltar"> <?php echo $row->proceso_pensamiento ?></span> |
</p>
<p>
    <?php echo $row->notas ?>
</p>

<?php
        $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "preguntas/explorar/",
            'atributos' => 'title="Explorar preguntas"'
        );
            
        $arr_menus['detalle'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => 'Vista previa',
            'link' => "preguntas/detalle/{$row->id}",
            'atributos' => 'title="Detalle de la pregunta"'
        );
            
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionarios',
            'link' => "preguntas/cuestionarios/{$row->id}",
            'atributos' => 'title="Cuestionarios que incluyen la pregunta"'
        );
            
        $arr_menus['estadisticas'] = array(
            'icono' => '<i class="fa fa-pie-chart"></i>',
            'texto' => 'Estadísticas',
            'link' => "preguntas/estadisticas/{$row->id}",
            'atributos' => 'title="Estadísticas de la pregunta"'
        );
            
        
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "preguntas/editar/edit/{$row->id}",
            'atributos' => 'title="Editar pregunta"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar');
        $elementos_rol[1] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar');
        $elementos_rol[2] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar');
        $elementos_rol[3] = array('explorar', 'detalle', 'estadisticas');
        $elementos_rol[4] = array('explorar', 'detalle', 'estadisticas');
        $elementos_rol[5] = array('explorar', 'detalle', 'estadisticas');
        $elementos_rol[8] = array('explorar', 'detalle', 'cuestionarios', 'estadisticas', 'editar');
        
        if ( $editable ) 
        {
            $elementos_rol[3][] = 'editar';
            $elementos_rol[4][] = 'editar';
            $elementos_rol[5][] = 'editar';
        }
        
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

