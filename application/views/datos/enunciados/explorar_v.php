<?php

    $seccion = $this->uri->segment(2);

    //Formulario
        $att_form = array(
            'class' => 'form-horizontal'
        );

        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-primary',
            'value' =>  'Buscar'
        );
        
    //Helper
        $this->load->helper('text');
?>

<?php $this->load->view('datos/enunciados/explorar_menu_v') ?>

<div class="mb-2" style="">
    <?= form_open($destino_form, $att_form) ?>
    <div class="d-flex justify-content-between">
        <div class="w320p">
            <div class="input-group">
                <?= form_input($att_q) ?>
                <div class="input-group-append">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        </div>
        <div class="">
            <?= $this->pagination->create_links(); ?>
        </div>
    </div>
    <?= form_close() ?>
</div>

<table class="table bg-white">
    <thead>
        <th width="30px">ID</th>
        <th>Título</th>
        <th>Creado por</th>
        <th width="120px"></th>
    </thead>

    <tbody>
        <?php foreach ($resultados->result() as $row_resultado) : ?>
            <tr style="border-bottom: 1px solid #f1f1f1;">
                <td class="warning"><?= $row_resultado->id ?></td>
                <td>
                    <b><?= anchor("enunciados/ver/{$row_resultado->id}", strip_tags($row_resultado->nombre_post), 'class="" title=""') ?></b>
                </td>
                
                <td>
                    <?= $this->App_model->nombre_usuario($row_resultado->usuario_id, 2) ?>
                </td>
                
                <td>
                    <?php if ( $this->session->userdata('rol_id') <= 2 ) : ?>                
                        <?= anchor("enunciados/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                        <?= $this->Pcrn->anchor_confirm("enunciados/eliminar/{$row_resultado->id}", '<i class="fa fa-times"></i>', 'class="a4" title=""') ?>
                    <?php endif ?>
                </td>
            </tr>

        <?php endforeach ?>
    </tbody>
</table>