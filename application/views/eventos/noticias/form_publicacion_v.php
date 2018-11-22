<?php

    //Formulario
    $att_contenido = array(
        'id'     => 'contenido',
        'name'   => 'contenido',
        'class'  => 'form-control',
        'rows'   => 2,
        'maxlength'   => 1400,  //Máximo de caracteres
        'value'  => '',
        'placeholder'   => 'Agregar publicación',
        'required'   => TRUE,
        'title'   => 'Escriba el texto de la publicación'
    );
    
    $att_texto_1 = array(
        'id'     => 'texto_1',
        'name'   => 'texto_1',
        'class'  => 'form-control',
        'value'  => '',
        'placeholder'   => 'URL',
        'title'   => 'Escriba la dirección URL'
    );
    
    $att_entero_1 = array(
        'id'     => 'entero_1',
        'name'   => 'entero_1',
        'class'  => 'hidden',
        'value'  => $config_form['entero_1']
    );
    
    $att_grupo_id = array(
        'id'     => 'grupo_id',
        'name'   => 'grupo_id',
        'class'  => 'hidden',
        'value'  => $config_form['grupo_id']
    );

    $att_submit = array(
        'class' =>  'btn btn-primary',
        'value' =>  'Publicar'
    );
?>

<script>
    $(document).ready(function()
    {
        $('#grupo_texto_1').hide();
        
        $('#link_institucion').click(function(){
            $('#grupo_id').val(0);
            $('#entero_1').val(1);
            $('#texto_alcance').html('<i class="fa fa-building"></i> Institución');
        });
        
        $('#link_profesores').click(function(){
            $('#grupo_id').val(0);
            $('#entero_1').val(3);
            $('#texto_alcance').html('<i class="fa fa-users"></i> Profesores');
        });
        
        $('.link_grupo').click(function(){
            var grupo_id = $(this).data('grupo_id');
            var texto_alcance = $(this).html();
            $('#grupo_id').val(grupo_id);
            $('#entero_1').val(2);
            $('#texto_alcance').html(texto_alcance);
        });
        
        $('#agregar_link').click(function()
        {
            $(this).toggleClass('btn-warning');
            $(this).toggleClass('btn-default');
            $('#grupo_texto_1').toggle('fast');
            $('#texto_1').focus();
        });
    });
</script>

<div class="noticia">
    <?= form_open($destino_form, $att_form) ?>
        
        <div class="form-group">
            <?= form_textarea($att_contenido); ?>
        </div>
    
        <div class="form-group" id="grupo_texto_1">
            <?= form_input($att_texto_1); ?>
        </div>
    
        <?= form_input($att_grupo_id); ?>
        <?= form_input($att_entero_1); ?>

        <div class="text-right">
            
            <div class="btn btn-default" id="agregar_link">
                <i class="fa fa-link"></i>
                Agregar Link
            </div>
            
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default w4" id="texto_alcance">
                    <?= $config_form['texto_alcance'] ?>
                </button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a>¿Quién verá esto?</a></li>
                    <li role="separator" class="divider"></li>
                    
                    <?php if ( $this->session->userdata('srol') == 'institucional' ){ ?>
                        <li>
                            <a href="#" id="link_institucion">
                                <i class="fa fa-building"></i> Institución
                            </a>
                        </li>
                    <?php } ?>
                        
                    <?php if ( in_array($this->session->userdata('rol_id'), array(3,4,5)) ){ ?>
                        <li>
                            <a href="#" id="link_profesores">
                                <i class="fa fa-users"></i> Profesores
                            </a>
                        </li>
                    <?php } ?>
                    
                    <?php foreach ($grupos->result() as $row_grupo) : ?>
                        <li>
                            <a href="#" class="link_grupo" data-grupo_id="<?= $row_grupo->id ?>">
                                <i class="fa fa-users"></i>
                                Grupo <?= $row_grupo->nombre_grupo ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?= form_submit($att_submit) ?>
        </div>  
    <?= form_close('') ?>
</div>