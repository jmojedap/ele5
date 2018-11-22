<?php
    $cant_elementos = $elementos->num_rows() - 1;

    $key_elemento = 0;
    
    $opciones = array();
    
    foreach ($elementos->result() as $row_elemento) {
        $opciones[] = $row_elemento->detalle;
    }
    
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
        
        var opciones = [];
        <?php for ($i = 0; $i <= $cant_elementos; $i++) { ?>
            opciones[<?= $i ?>] = '<?= $opciones[$i] ?>';
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
            var str = $(this).attr('id');
            var arr = str.split('-');
            respuesta_arr[arr[0]] = parseInt(arr[1]);
            respuesta = JSON.stringify(respuesta_arr);
            
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

<?php foreach ($imagenes->result() as $row_imagen) : ?>
    <?= img($carpeta_img . $row_imagen->archivo) ?>
<?php endforeach ?>

<hr/>

<table class="tablesorter">
    <thead>
        <th>Pos X</th>
        <th>Pos Y</th>
        <th>texto</th>
        <th>Opciones</th>
    </thead>
    <tbody>
        <?php foreach ($elementos->result() as $row_elemento) : ?>
            <tr>
                <td><?= $row_elemento->x ?></td>
                <td><?= $row_elemento->y ?></td>
                <td><?= $row_elemento->texto ?></td>
                <td>
                    <?php foreach ($opciones as $key => $opcion) : ?>
                        <span class="etiqueta informacion opcion_quiz" id="<?= $key_elemento . '-' . $key ?>"><?= $opcion ?></span>
                    <?php endforeach ?>
                </td>
            </tr>
            
            <?php $key_elemento += 1; ?>
            
        <?php endforeach ?>
    </tbody>
</table>
    
<p>
    <div id="enviar" class="button orange">Enviar</div>
</p>



<h4 id="resultado_correcto" class="alert_success">Correcto</h4>
<h4 id="resultado_incorrecto" class="alert_error">Incorrecto</h4>