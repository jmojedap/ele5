<?php
    
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['actividad'] = array(
            'icono' => '',
            'texto' => 'Actividad',
            'link' => "usuarios/actividad/{$row->id}/{$row->username}",
            'atributos' => 'title="Actividad del usuario en la Plataforma"'
        );
        
        $arr_menus['contrasena'] = array(
            'icono' => '<i class="fa fa-lock"></i>',
            'texto' => '',
            'link' => "usuarios/contrasena",
            'atributos' => 'title="Cambio de contraseña de usuario"'
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "usuarios/editar/edit/{$row->id}",
            'atributos' => 'title="Editar datos de usuario"'
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('actividad', 'editar', 'master_login');
        $elementos_rol[1] = array('actividad', 'editar', 'master_login');
        $elementos_rol[2] = array('actividad', 'editar');
        $elementos_rol[3] = array();
        $elementos_rol[4] = array();
        $elementos_rol[5] = array();
        $elementos_rol[6] = array();
        $elementos_rol[7] = array();
        $elementos_rol[8] = array();
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Elementos propios, contraseña y editar
        if ( $row->id == $this->session->userdata('usuario_id') ) {
            $elementos[] = 'contrasena';
        }
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
    
?>
    
<p>
    <span class="resaltar"><?= $this->Item_model->nombre(6, $row->rol_id); ?></span> 
    
     
    <span class="suave">| Username:</span>
    <span class="resaltar"><?= $row->username ?></span>
</p>

<?= $this->load->view('app/menu_v', $data_menu)?>
<?= $this->load->view($vista_b)?>
            
