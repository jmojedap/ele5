<?php
        
    //Clases menú
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'nuevo_grupo' ) { $seccion = 'grupos'; }

        $clases[$seccion] = 'active';
        
        $explorar_add = '';
        if ( $this->session->userdata('rol_id') > 3 )
        {
            $explorar_add = '?f1=1';
        }

    
    //Atributos de los elementos del menú
        $arr_menus['ap_explorar'] = array(
            'icono' => '<i class="fa fa-arrow-left"></i>',
            'texto' => 'Explorar',
            'link' => "posts/ap_explorar/{$explorar_add}",
            'atributos' => ''
        );
        
        $arr_menus['ap_editar'] = array(
            'icono' => '<i class="fa fa-pencil-alt"></i>',
            'texto' => 'Editar',
            'link' => "posts/ap_editar/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['ap_leer'] = array(
            'icono' => '<i class="fa fa-laptop"></i>',
            'texto' => 'Abrir',
            'link' => "posts/ap_leer/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['ap_instituciones'] = array(
            'icono' => '<i class="fa fa-university"></i>',
            'texto' => 'Instituciones',
            'link' => "posts/ap_instituciones/{$row->id}",
            'atributos' => 'title="Instituciones asignadas"'
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('ap_explorar', 'ap_leer', 'ap_instituciones', 'ap_editar');
        $elementos_rol[1] = array('ap_explorar', 'ap_leer', 'ap_instituciones', 'ap_editar');
        $elementos_rol[2] = array('ap_explorar', 'ap_leer', 'ap_instituciones', 'ap_editar');
        
        $elementos_rol[3] = array('ap_explorar', 'ap_leer');
        $elementos_rol[4] = array('ap_explorar', 'ap_leer');
        $elementos_rol[5] = array('ap_explorar', 'ap_leer');
        
        $elementos_rol[6] = array('ap_explorar', 'ap_leer');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
?>

<?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
    <p>
        <span class="suave">Tipo </span>
        <span class="resaltar">
            <?= $row->nombre ?> <?= $this->Item_model->nombre(33, $row->tipo_id) ?>
        </span>
        <span class="suave"> | </span>
    </p>
<?php } ?>

<?php $this->load->view('comunes/bs4/menu_v', $data_menu); ?>    