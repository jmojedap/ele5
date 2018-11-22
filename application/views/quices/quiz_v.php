<div class="sep2">
    <p>
        <span class="etiqueta nivel w1"><?= $row_tema->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_tema->area_id) ?>
        <span class="etiqueta informacion w1"><?php echo $this->Item_model->nombre(9, $row->tipo_quiz_id) ?></span>
        <span class="suave"> | </span>
        
        <span class="suave">Tema:</span>
        <span class="resaltar"><?= anchor("temas/quices/{$row->tema_id}", $row_tema->nombre_tema, 'class="" title=""') ?></span>
        <span class="suave"> | </span>

        <span class="suave">Código:</span>
        <span class="resaltar"> <?= $row->cod_quiz ?></span>
        <span class="suave"> | </span>
        
        <span class="suave">Editado por:</span>
        <span class="resaltar"> <?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?></span>
        <span class="suave"> | </span>

        <span class="suave">Editado:</span>
        <span class="resaltar"> <?= $this->Pcrn->fecha_formato($row->editado, 'M-d') ?></span>
        <span class="suave"> | </span>
        
        <span class="suave">Hace:</span>
        <span class="resaltar"> <?= $this->Pcrn->tiempo_hace($row->editado) ?></span>
        <span class="suave"> | </span>
    </p>
    <p><?= $row->descripcion ?></p>
</div>

<?php
    $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "quices/explorar/",
            'atributos' => 'title="Explorar evidencias"'
        );
        
        $arr_menus['temas'] = array(
            'icono' => '<i class="fa fa-bars"></i>',
            'texto' => 'Temas',
            'link' => "quices/temas/{$row->id}",
            'atributos' => 'title="Temas asociados a la evidencia"'
        );
            
        $arr_menus['construir'] = array(
            'icono' => '<i class="fa fa-wrench"></i>',
            'texto' => 'Construir',
            'link' => "quices/construir/{$row->id}",
            'atributos' => 'title="Explorar evidencias"'
        );
        
        $arr_menus['resolver'] = array(
            'icono' => '<i class="fa fa-external-link"></i>',
            'texto' => 'Vista previa',
            'link' => "quices/resolver/{$row->id}",
            'atributos' => 'title="Vista previa de la Evidencia" target="_blank"'
        );
            
        $arr_menus['elementos'] = array(
            'icono' => '<i class="fa fa-book"></i>',
            'texto' => 'Elementos',
            'link' => "quices/elementos/{$row->id}",
            'atributos' => 'title="Elementos de la evidencia"'
        );
            
        $arr_menus['detalle'] = array(
            'icono' => '<i class="fa fa-book"></i>',
            'texto' => 'Detalle',
            'link' => "quices/detalle/{$row->id}",
            'atributos' => 'title="Detalle de los elementos de la evidencia"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "quices/editar/edit/{$row->id}",
            'atributos' => 'title="Editar evidencia"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'temas', 'construir', 'resolver', 'elementos', 'detalle', 'editar');
        $elementos_rol[1] = array('explorar', 'temas', 'construir', 'resolver', 'editar');
        $elementos_rol[2] = array('explorar', 'temas', 'construir', 'resolver', 'editar');
        
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