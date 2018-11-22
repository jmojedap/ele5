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


        $att_submit = array(
            'class' =>  'button orange',
            'value' =>  'Buscar'
        );
?>

<div class="div2">
    <p>
        Resultados encontrados: <span class="resaltar"><?= $cant_resultados?></span>
    </p>
</div>

<div class="div2" style="overflow: hidden;">
    <?= form_open("busquedas/{$seccion}", $att_form) ?>
        <div class="casilla w5">
            <?= form_input($att_q) ?>
        </div>
        <div class="casilla"><?= form_submit($att_submit) ?></div>
    <?= form_close() ?>
</div>

<div class="div1" style="text-align: center;">
    <?= $this->pagination->create_links(); ?>
</div>

<table class="tablesorter">
    <thead>
        <th width="30px">Cód.</th>
        <th>Institución</th>
        <th>Ejecutivo</th>
        <th width="70px"></th>
    </thead>

    <tbody>
        <?php foreach ($resultados->result() as $row_institucion) : ?>
            <tr style="border-bottom: 1px solid #f1f1f1;">
                <td><span class="etiqueta primario w1"><?= $row_institucion->id ?></span></td>
                <td>
                    <b><?= anchor("instituciones/usuarios/{$row_institucion->id}", $row_institucion->nombre_institucion, 'class="" title=""') ?></b>
                    <br/>
                    <?= $this->Pcrn->si_nulo($row_institucion->lugar_id, '', $this->App_model->nombre_lugar($row_institucion->lugar_id)) ?>
                    
                    <?php if ( strlen($row_institucion->direccion) > 0 OR strlen($row_institucion->telefono) > 0 ) : ?>                
                        <br/>
                        <span class="resaltar"><i class="fa fa-phone-square"></i></span>
                        <span class="suave"><?= $row_institucion->telefono ?></span>
                        
                        <span class="resaltar"><i class="fa fa-home"></i></span>
                        <span class="suave"><?= $row_institucion->direccion ?></span>
                    <?php endif ?>
                        
                    <?php if ( strlen($row_institucion->pagina_web) > 0 ){ ?>
                        <br/>
                        <?= anchor($row_institucion->pagina_web, str_replace('http://', '', $row_institucion->pagina_web), 'class="" target="_blank"') ?>
                    <?php } ?>
                    
                </td>
                <td>
                    <?= $this->App_model->nombre_usuario($row_institucion->ejecutivo_id, 2) ?>
                </td>
                <td>
                    <?= anchor("instituciones/editar/edit/{$row_institucion->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    <?= $this->Pcrn->anchor_confirm("instituciones/eliminar/{$row_institucion->id}", '<i class="fa fa-times"></i>', 'class="a4" title=""') ?>
                </td>
            </tr>

        <?php endforeach ?>
    </tbody>
</table>