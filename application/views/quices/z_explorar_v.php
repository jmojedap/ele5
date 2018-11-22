<?php

    $seccion = $this->uri->segment(2);

    //Formulario
        $att_form = array(
            'class' => 'form1'
        );

        $att_q = array(
            'class' =>  'input1',
            'name' => 'q',
            'value' => $busqueda['q']
        );


        //Opciones de dropdowns
        $opciones_area = $this->Item_model->opciones_id('categoria_id = 1', 'Área');
        $opciones_nivel = $this->App_model->opciones_nivel('item_largo', 'Nivel');
        $opciones_tipo = $this->Item_model->opciones('categoria_id = 9', 'Tipo evidencia');
        
        

        $att_submit = array(
            'class' =>  'button orange',
            'value' =>  'Buscar'
        );

?>

<div class="div2">
    Quices encontrados: <span class="resaltar"><?= $cant_resultados?></span>
</div>

<div class="div2" style="overflow: hidden;">
    <?= form_open("busquedas/explorar_redirect/quices/", $att_form) ?>
        <div class="casilla w5"><?= form_input($att_q) ?></div>
        <div class="casilla w4"><?= form_dropdown('area_id', $opciones_area, $busqueda['a'], 'title="Filtrar por área"'); ?></div>
        <div class="casilla w3"><?= form_dropdown('nivel', $opciones_nivel, $busqueda['n'], 'title="Filtrar por nivel"'); ?></div>
        <div class="casilla w3"><?= form_dropdown('tipo_id', $opciones_tipo, $busqueda['tp'], 'title="Filtrar por nivel"'); ?></div>
        <div class="casilla"><?= form_submit($att_submit) ?></div>
    <?= form_close('') ?>
</div>

<?php if ( $busqueda['e'] != 0 ){ ?>
    <h4 class="alert_success"><?= 'Se cargaron los datos de ' . $cant_resultados . ' quices' ?></h4>
<?php } ?>

<div class="div1" style="text-align: center;">
    <?= $this->pagination->create_links(); ?>
</div>
    
<hr/>

<div class="section group">
    
    <!-- Lista de quices   -->
    
    <?php if( $resultados  != NULL ){ ?>
        
        <table class="tablesorter" cellspacing="0">
            <thead>
                <tr>
                    
                    <th width="45px">Id</th>
                    <th width="100px">Cód. quiz</th>
                    <th>Nombre quiz</th>
                    <th>Creado por</th>
                    <th>Elementos</th>
                    <th>Vista previa</th>
                    <th width="60px">Nivel</th>
                    <th>Área</th>
                    <th>Tipo</th>
                    
                    
                    <?php if ( $this->session->userdata('rol_id') <= 2 ) : ?>                
                        <th width="70px"></th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados->result() as $row_quiz){ ?>
                    <?php
                        $cant_elementos = $this->Pcrn->num_registros('quiz_elemento', "quiz_id = {$row_quiz->id}");
                    ?>
                    
                    <tr>
                        <td><span class="etiqueta primario"><?= $row_quiz->id ?></span></td>
                        <td><?= $row_quiz->cod_quiz ?></td>
                        <td><?= anchor("quices/construir/$row_quiz->id", $row_quiz->nombre_quiz) ?></td>
                        <td><?= $this->App_model->nombre_usuario($row_quiz->usuario_id, 2) ?></td>
                        <td><?= $cant_elementos ?></td>
                        <td><?= anchor("quices/resolver/$row_quiz->id", '<i class="fa fa-external-link"></i>', 'target="_blank" class="a2 w1"') ?></td>
                        <td><span class="etiqueta nivel w1"><?= $row_quiz->nivel ?></span></td>
                        <td>
                            <?= $this->App_model->etiqueta_area($row_quiz->area_id) ?>
                        </td>
                        <td><?php //echo $this->Item_model->nombre() . $row_quiz->tipo_quiz_id ?></td>
                        
                        <?php if ( $this->session->userdata('rol_id') <= 2 ) : ?>                
                            <td>
                                <?= anchor("quices/editar/edit/{$row_quiz->id}", '<i class="fa fa-pencil"></i>', 'class="a4"') ?>
                                <?= $this->Pcrn->anchor_confirm("quices/eliminar/{$row_quiz->id}", '<i class="fa fa-times"></i>', 'class="a4"') ?>
                            </td>
                        <?php endif ?>
                        
                    </tr>

                <?php } //foreach ?>
            </tbody>
        </table>

        
    <?php } //if ?>
    
    
</div>