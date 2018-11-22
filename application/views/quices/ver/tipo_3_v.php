<?php
    $cant_elementos = $elementos->num_rows() - 1;
    $key_elemento = 0;
    
    $opciones['[1,1,0]'] = 'Solo I y II';
    $opciones['[0,1,1]'] = 'Solo II y III';
    $opciones['[1,1,1]'] = 'I, II y III';
    $opciones['[1,0,1]'] = 'Solo I y III';
    
    $carpeta_img = RUTA_UPLOADS . 'quices/';
?>

<style>
    .opcion_quiz{
        cursor: pointer;
    }
</style>

<script>
    //Variables
        var respuesta_arr = [];
        <?php for ($i = 0; $i <= $cant_elementos; $i++) { ?>
            respuesta_arr[<?= $i ?>] = '';
        <?php } ?>
        var respuesta = JSON.stringify(respuesta_arr);
        var clave = '<?= $row->clave ?>';
        var resultado = 0;
        var quiz_id = <?= $row->id ?>;
        var usuario_id = <?= $this->session->userdata('usuario_id') ?>
    
    $(document).ready(function(){
        
        $('#resultado_incorrecto').hide();
        $('#resultado_correcto').hide();
        $('#respuesta_quiz').val(respuesta);
        
        
        $('.opcion_quiz').click(function() {
            //var opcion_id = $(this).attr('id');
            respuesta = $(this).attr('id');
            $('#respuesta_quiz').html(respuesta);
            
            $('.opcion_quiz').removeClass('exito');
            $(this).addClass('exito');
        });
        
        
        $('#enviar').click(function(){
            actualizar_resultado();
            guardar_resultado();
        });
    });
    
    function actualizar_resultado()
    {
        if ( respuesta === clave ) {
            resultado = 1;
            $('#resultado_correcto').show();
            $('#resultado_incorrecto').hide();
        } else {
            resultado = 0;
            $('#resultado_correcto').hide();
            $('#resultado_incorrecto').show();
        }
        
    }
    
    //Guardar resultado al resolver el quiz
    function guardar_resultado(){
        
        $.ajax({        
            type: 'POST',
            url: '<?= base_url() ?>quices/guardar_resultado',
            data: {
                usuario_id : usuario_id,
                quiz_id : quiz_id,
                resultado : resultado
            }
        });

    }
    
    
</script>

<div class="div2">
    <?php foreach ($imagenes->result() as $row_imagen) : ?>
        <?= img($carpeta_img . $row_imagen->archivo) ?>
        <hr/>
    <?php endforeach ?>
</div>

<ol style="list-style-type: upper-roman">
    <?php foreach ($elementos->result() as $row_elemento) : ?>
            <li>
                <?= $row_elemento->texto ?>
            </li>
    <?php endforeach ?>
</ol>

<h3>Opciones</h3>

<ul>
    <?php foreach ($opciones as $key => $opcion) : ?>
    <li>
        <span class="etiqueta informacion opcion_quiz" id="<?= $key ?>"><i class="fa fa-arrow-circle-right"></i></span>
        <?= $opcion ?>
    </li>

    <?php endforeach ?>
</ul>
    
<p>
    <div id="enviar" class="button orange">Enviar</div>
</p>

<h4 id="resultado_correcto" class="alert_success">Correcto</h4>
<h4 id="resultado_incorrecto" class="alert_error">Incorrecto</h4>