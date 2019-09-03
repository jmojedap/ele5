<?php

    $seccion = $this->uri->segment(2);

    //Formulario
        $att_form = array(
            'class' => 'form-inline'
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
          
<?php $this->load->view('datos/ayudas/explorar_menu_v'); ?>

<div class="row">
    <div class="col-md-6 sep1">
        <?= form_open("datos/ayudas/{$controlador}", $att_form) ?>
        <?= form_input($att_q) ?>
        <?= form_submit($att_submit) ?>
        <?= form_close() ?>
    </div>

    <div class="col-md-3 col-xs-6 sep1">
        
    </div>

    <div class="col-md-3 col-xs-6 sep1">
        <div class="pull-right">
            <p id="seleccionados"></p>
            <?= $this->pagination->create_links(); ?>
        </div>
    </div>
</div>


<table class="table table-default bg-blanco">
    <thead>
        <th>Temas de ayuda</th>
    </thead>

    <tbody>
        <?php foreach ($resultados->result() as $row_resultado) : ?>
            <tr>
                <td>
                    <b>
                        <a href="https://www.plataformaenlinea.com/ayuda/?p=<?= $row_resultado->abreviatura ?>" target="_blank">
                            <?= $row_resultado->item_largo ?>
                        </a>                        
                    </b>
                    <p>
                        <?= $row_resultado->descripcion ?>
                    </p>
                </td>
            </tr>

        <?php endforeach ?>
    </tbody>
</table>