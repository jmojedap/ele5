<?php

    $seccion = $this->uri->segment(2);

    //Formulario
        $att_form = array(
            'class' => 'form1'
        );

        $att_q = array(
            'class' =>  'input1',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );

        //Opciones de dropdowns
        $opciones_rol = $this->App_model->opciones_item('categoria_id = 6', TRUE);


        $att_submit = array(
            'class' =>  'button orange',
            'value' =>  'Buscar'
        );
?>

<div class="div2">
    <p>
        Resultados encontrados: <span class="resaltar"><?= $cant_resultados?></span> | 
        Cuestionarios con nombre como '<span class="resaltar"><?= $busqueda['q'] ?></span>'
    </p>
</div>

<div class="div2" style="overflow: hidden;">
    <?= form_open("busquedas/{$seccion}", $att_form) ?>
        <div class="casilla w5">
            <?= form_input($att_q) ?>
        </div>
        <div class="casilla w4"><?= form_dropdown('rol_id', $opciones_rol, $busqueda['rol'], 'title="Filtrar por tipo de usuario"'); ?></div>
        <div class="casilla"><?= form_submit($att_submit) ?></div>
    <?= form_close() ?>
</div>

<div class="div1" style="text-align: center;">
    <?= $this->pagination->create_links(); ?>
</div>

<hr/>

<div class="section group">
    
    <!-- Lista de usuarios   -->
    
    <?php if( $resultados  != NULL ): ?>
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    <?php if ( $this->session->userdata('rol_id') <= 1 ) : ?>                
                        <th width="50px">Login</th>
                    <?php endif ?>
                    <th>Nombre</th>
                    <th>Username</th>
                    <th>Institución</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados->result() as $row_usuario): ?>
                    <?php
                        //Variables
                        $nombre_usuario = "{$row_usuario->nombre} {$row_usuario->apellidos}";
                        if ( ! in_array($this->session->userdata('rol_id'), array(6,7) ) ){
                            $nombre_usuario = anchor("usuarios/actividad/$row_usuario->id", $nombre_usuario);
                        }
                    ?>

                    <tr>
                        <?php if ( $this->session->userdata('rol_id') <= 1 ) : ?>                
                            <td><?= anchor("develop/ml/{$row_usuario->username}", '<div class="a2 w1"><i class="fa fa-sign-in"></i></div>', 'class="" title="Ingresar a la plataforma con este usuario"') ?></td>
                        <?php endif ?>
                        <td><?= $nombre_usuario ?></td>
                        <td><?= $row_usuario->username ?></td>
                        <td><?= $this->App_model->nombre_institucion($row_usuario->institucion_id) ?></td>
                        <td><?= $this->Item_model->nombre(6, $row_usuario->rol_id); ?></td>
                    </tr>

                <?php endforeach //foreach ?>
            </tbody>
        </table>

        
    <?php endif //if ?>
    
    
</div>