<p>
    <span class="resaltar">
        <i class="fa fa-user"></i>
    </span>
    <?= anchor("usuarios/actividad/{$row->usuario_id}", $this->App_model->nombre_usuario($row->usuario_id, 2)) ?>
    
    <span class="suave"> | </span>
    
    <span class="resaltar">
        Tipo
    </span>
    <?= $this->Item_model->nombre(61, $row->tipo_id) ?>
</p>

<?php
    $seccion = $this->uri->segment(2);
        //if ( $this->uri->segment(2) == 'otra_seccion' ) { $seccion = 'seccion'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => 'Explorar',
            'link' => "mensajes/explorar/",
            'atributos' => 'title="Explorar conversaciones"'
        );
        
        $arr_menus['mensajes'] = array(
            'icono' => '<i class="fa fa-comment-o"></i>',
            'texto' => 'Mensajes',
            'link' => "mensajes/mensajes/{$row->id}",
            'atributos' => 'title="Usuarios en la conversación"'
        );
        
        $arr_menus['usuarios'] = array(
            'icono' => '<i class="fa fa-users"></i>',
            'texto' => 'Participantes',
            'link' => "mensajes/usuarios/{$row->id}",
            'atributos' => 'title="Usuarios en la conversación"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'mensajes', 'usuarios');
        $elementos_rol[1] = array('explorar', 'mensajes', 'usuarios');
        $elementos_rol[6] = array('explorar', 'mensajes', 'usuarios');
        
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