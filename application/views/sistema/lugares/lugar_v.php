<?php
    //Clases menú
        $seccion = $this->uri->segment(2);
        $clases[$seccion] = 'active';

        $titulo_sublugares = $this->Lugar_model->titulo_sublugares($row->tipo_id);

        if ( $seccion == 'sublugares' ) {
            $titulo_sublugares .= ' (' . $sublugares->num_rows() . ')';
        }
    
    //Atributos de los elementos del menú
        $arr_menus['explorar'] = array(
            'icono' => '<i class="fa fa-list-alt"></i>',
            'texto' => '',
            'link' => "lugares/explorar/?tp={$row->tipo_id}",
            'atributos' => ''
        );
        
        $arr_menus['sublugares'] = array(
            'icono' => '',
            'texto' => $titulo_sublugares,
            'link' => "lugares/sublugares/{$row->id}",
            'atributos' => ''
        );
        
        $arr_menus['editar'] = array(
            'icono' => '<i class="fa fa-pencil"></i>',
            'texto' => '',
            'link' => "lugares/editar/edit/{$row->id}",
            'atributos' => ''
        );
        
    //Elementos de menú para cada rol
        $elementos_rol[0] = array('explorar', 'sublugares', 'editar');
        
    //Definiendo menú mostrar
        $elementos = $elementos_rol[$this->session->userdata('rol_id')];
        
    //Array data para la vista: app/menu_v
        $data_menu['elementos'] = $elementos;
        $data_menu['clases'] = $clases;
        $data_menu['arr_menus'] = $arr_menus;
        $data_menu['seccion'] = $seccion;
?>
    
<div class="btn-group sep2" role="group">
    <?php if ( $row->tipo_id > 0 ){ ?>
        <?= anchor("lugares/sublugares/{$row->continente_id}", $this->App_model->nombre_lugar($row->continente_id), 'class="btn btn-default"') ?>
    <?php } ?>
    
    <?php if ( $row->tipo_id > 1 ){ ?>
        <?= anchor("lugares/sublugares/{$row->pais_id}", $row->pais, 'class="btn btn-default"') ?>
    <?php } ?>
        
    <?php if ( $row->tipo_id > 2 ){ ?>
        <?= anchor("lugares/sublugares/{$row->region_id}", $row->region, 'class="btn btn-default"') ?>
    <?php } ?>
    
    <?php if ( $row->tipo_id > 3 ){ ?>
        <?= anchor("lugares/sublugares/{$row->region_id}", $row->nombre_lugar, 'class="btn btn-default"') ?>
    <?php } ?>
</div>

<?php $this->load->view('comunes/menu_v', $data_menu); ?>
<?php $this->load->view($vista_b) ?>