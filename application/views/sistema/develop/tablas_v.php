<?php $this->load->view('assets/grocery_crud'); ?>
<?php $this->load->view('sistema/develop/database_menu_v'); ?>


<?php

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

    $tablas = $this->db->get_where('sis_tabla', 'id NOT IN (1040)');
    
    //Att form
        $att_condicion = array(
            'class' =>  'form-control',
            'name' =>  'condicion',
            'placeholder' =>  'Where...'
        );
        
        if ( strlen($this->input->post('condicion')) > 0 ) 
        {
            $att_condicion['value'] = $this->input->post('condicion');
        }
        
        $att_submit = array(
            'class' => 'btn btn-primary',
            'value' => 'Filtrar'
        );
?>

<div class="bs-caja">
    <div class="row">
        
        <div class="col-md-2 sep2">
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default">Seleccione la tabla</button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <?php foreach ($tablas->result() as $row_tabla) : ?>
                        <?php
                            $clase_tabla = '';
                            if ( $nombre_tabla == $row_tabla->nombre_tabla ) { $clase_tabla .= 'active'; }
                        ?>
                        <li>
                            <?= anchor("develop/tablas/{$row_tabla->nombre_tabla}", $row_tabla->nombre_tabla, 'class="' . $clase_tabla . '"') ?>
                        </li>
                        
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
        
        <div class="col-md-10 sep2">
            <?= form_open("develop/tablas/{$nombre_tabla}/", $att_form) ?>
            <?= form_input($att_condicion); ?>
            <?= form_submit($att_submit) ?>
            <?= form_close('') ?>
        </div>
    </div>
</div>

<?= $output; ?>
    
