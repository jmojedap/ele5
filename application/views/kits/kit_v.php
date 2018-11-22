<p>                
    <span class="suave">Creado por:</span>
    <span class="resaltar"> <?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?></span> |

    <span class="suave">Editado:</span>
    <span class="resaltar"> <?= $this->Pcrn->fecha_formato($row->editado, 'Y-M-d h:i a') ?></span> |
    
    <span class="suave">Descripción:</span>
    <span class="suave"><?= $row->descripcion ?></span> |
</p>

<?php
        $seccion = $this->uri->segment(2);
        if ( $this->uri->segment(2) == 'importar_flipbooks' ) { $seccion = 'importar_elementos'; }
        if ( $this->uri->segment(2) == 'importar_elementos_e' ) { $seccion = 'importar_elementos'; }

        $clases[$seccion] = 'active';
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-caret-left"></i>',
            'texto' => 'Kits',
            'link' => "kits/explorar/",
            'atributos' => 'title="Explorar kits"'
        );
            
        $arr_menus['flipbooks'] = array(
            'icono' => '<i class="fa fa-book"></i>',
            'texto' => 'Contenidos',
            'link' => "kits/flipbooks/{$row->id}",
            'atributos' => 'title="Contenidos del kit"'
        );
            
        $arr_menus['cuestionarios'] = array(
            'icono' => '<i class="fa fa-question"></i>',
            'texto' => 'Cuestionarios',
            'link' => "kits/cuestionarios/{$row->id}",
            'atributos' => 'title="Cuestionarios del kit"'
        );
            
        $arr_menus['instituciones'] = array(
            'icono' => '<i class="fa fa-bank"></i>',
            'texto' => 'Instituciones',
            'link' => "kits/instituciones/{$row->id}",
            'atributos' => 'title="Instituciones con el kit asignado"'
        );
            
        $arr_menus['importar_elementos'] = array(
            'icono' => '<i class="fa fa-file-excel-o"></i>',
            'texto' => 'Importar elementos',
            'link' => "kits/importar_elementos/{$row->id}",
            'atributos' => 'title="Importar elementos del kit con archivo MS Excel"'
        );
            
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => 'Editar',
            'link' => "kits/editar/edit/{$row->id}",
            'atributos' => 'title="Editar kit"'
        );
        
    //Elementos de menú según el rol del visitante
        $elementos_rol[0] = array('explorar', 'flipbooks', 'cuestionarios', 'instituciones', 'importar_elementos', 'editar');
        $elementos_rol[1] = array('explorar', 'flipbooks', 'cuestionarios', 'instituciones', 'importar_elementos', 'editar');
        $elementos_rol[2] = array('explorar', 'flipbooks', 'cuestionarios', 'instituciones', 'importar_elementos', 'editar');
        
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