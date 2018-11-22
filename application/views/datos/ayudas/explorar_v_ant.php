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
        
    //Helper
        $this->load->helper('text');
?>

<?= $this->load->view('datos/ayudas/explorar_menu_v') ?>

<div class="sep2" style="overflow: hidden;">
    <?= form_open("datos/ayudas_explorar", $att_form) ?>
        <div class="casilla w5">
            <?= form_input($att_q) ?>
        </div>
        <div class="casilla"><?= form_submit($att_submit) ?></div>
    <?= form_close() ?>
</div>

<div class="div1" style="text-align: center;">
    <?= $this->pagination->create_links(); ?>
</div>

<table class="table table-default bg-blanco">
    <thead>
        <th width="30px">ID</th>
        <th>Título</th>
        <th>Descripción</th>
        <th width="70px"></th>
    </thead>

    <tbody>
        <?php foreach ($resultados->result() as $row_resultado) : ?>
            <tr style="border-bottom: 1px solid #f1f1f1;">
                <td><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                <td>
                    <b>
                        <a href="http://plataformaenlinea.com/ayuda/?p=<?= $row_resultado->abreviatura ?>" target="_blank">
                            <?= $row_resultado->item_largo ?>
                        </a>
                    </b>
                </td>
                
                <td>
                    <?= word_limiter($row_resultado->descripcion, 20) ?>
                </td>
                
                <td>
                    <?php if ( $this->session->userdata('rol_id') <= 2 ) : ?>                
                        <?= anchor("datos/ayudas_editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                        <?= $this->Pcrn->anchor_confirm("datos/ayudas_eliminar/{$row_resultado->id}", '<i class="fa fa-times"></i>', 'class="a4" title=""') ?>
                    <?php endif ?>
                </td>
            </tr>

        <?php endforeach ?>
    </tbody>
</table>