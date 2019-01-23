<?php
    $cant_elementos = $elementos->num_rows() - 1;

    $key_elemento = 0;
    //respuesta
        foreach ($elementos->result() as $row_elemento) {
            $respuesta .= '"",';
        }

        $respuesta = substr($respuesta, 0, -1);

        $respuesta .= "'[{$respuesta}]'";
    
    //Imagen principal
        $att_img = array(
            'class' =>  'principal'
        );
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
</script>

<script>
    
    
    $(document).ready(function(){
        
        $('#resultado_incorrecto').hide();
        $('#resultado_correcto').hide();
        
        
        $('.casilla_quiz').change(function() {

            var elemento_id = $(this).attr('id');
            var valor = parseInt($(this).val());
            
            respuesta_arr[elemento_id] = valor;
            respuesta = JSON.stringify(respuesta_arr);
            
            //$('#respuesta_quiz').html(respuesta);
            
            //alert('hola_mundo');
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
            toastr['success']('¡Correcto, felicitaciones!');
        } else {
            resultado = 0;
            toastr['warning']('Incorrecto, inténtalo de nuevo');
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

<p id="respuesta_quiz"></p>

<?php if ( strlen($imagen['src']) > 0 ){ ?>
    <div class="div2" style="text-align: center;">
        <?php $att_img['src'] = $imagen['src'] ?>
        <?= img($att_img) ?>
    </div>
<?php } ?>


<?php foreach ($elementos->result() as $row_elemento) : ?>
    <?php
        $opcion_vacia[''] = '[ Seleccione ]';
        $opciones_elemento = json_decode($row_elemento->detalle);
        $opciones = array_merge($opcion_vacia, $opciones_elemento);
        $dropdown = form_dropdown($key_elemento, $opciones, '', 'id="' . $key_elemento . '" class="casilla_quiz"');
    ?>
    
    <p>
        <i class="fa fa-caret-right resaltar"></i>
        <?= str_replace('#casilla', $dropdown, $row_elemento->texto) ?>
    </p>
    
    <?php $key_elemento += 1; ?>
<?php endforeach ?>