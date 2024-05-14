<?php $this->load->view('assets/chosen_jquery'); ?>

<?php

    $editando = FALSE;
    if ( $this->uri->segment(2) == 'editar' )
    {
        $editando = TRUE;
    } else {
        $valores_form['nivel'] = '095';
    }

    //Formulario    
        $att_form = array(
            'class' =>  'form-horizontal'
        );

        $att_nombre_post = array(
            'id'     => 'campo-nombre_post',
            'name'   => 'nombre_post',
            'class'  => 'form-control',
            'required'  => TRUE,
            'value'  => $valores_form['nombre_post'],
            'placeholder'   => 'Nombre o título del post'
        );
        
        //Opciones
            $opciones_tipo = $this->Item_model->opciones('categoria_id = 33');


        $att_submit = array(
            'class'  => 'btn btn-block btn-info',
            'value'  => 'Guardar'
        );
    
?>

<?php if ( ! is_null($vista_menu) ) { ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-body">
        <?= form_open($destino_form, $att_form) ?>
        
            <?php if ( ! $editando) { ?>
                
            <?php } ?>

            <div class="form-group">
                <label for="tipo_id" class="col-sm-4 control-label">Tipo *</label>
                <div class="col-sm-8">
                    <?= form_dropdown('tipo_id', $opciones_tipo, $valores_form['tipo_id'], 'id="campo-nivel" class="form-control" required title="Seleccione el tipo de Post"'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="nombre_post" class="col-sm-4 control-label">Nombre *</label>
                <div class="col-sm-8">
                    <?= form_input($att_nombre_post) ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <?= form_submit($att_submit) ?>
                </div>
            </div>
        <?= form_close() ?>
    </div>
</div>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>