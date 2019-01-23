<?php
    $cant_elementos = $elementos->num_rows() - 1;
    $key_elemento = 0;
    
    $opciones['[1,1,0]'] = 'Solo I y II';
    $opciones['[0,1,1]'] = 'Solo II y III';
    $opciones['[1,1,1]'] = 'I, II y III';
    $opciones['[1,0,1]'] = 'Solo I y III';
    
    //Imagen principal
    $att_img = array(
        'class' =>  'principal'
    );
?>

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
            respuesta = $(this).attr('id');
            $('#respuesta_quiz').html(respuesta);
            
            $('.opcion_quiz').removeClass('actual');
            $(this).addClass('actual');
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

<?php if ( strlen($imagen['src']) > 0 ){ ?>
    <div class="text-center">
        <img class="img-thumbnail p-3 principal" alt="Imagen principal de evidencia" src="<?php echo $imagen['src'] ?>">
    </div>
<?php } ?>

<ol style="list-style-type: upper-roman">
    <?php foreach ($elementos->result() as $row_elemento) : ?>
        <li>
            <?php echo $row_elemento->texto ?>
        </li>
    <?php endforeach ?>
</ol>

<ul class="opciones_quiz">
    <?php foreach ($opciones as $key => $opcion) : ?>
        <li>
            <i class="fa fa-caret-right resaltar"></i>
            <div class="opcion_quiz" id="<?= $key ?>"><?= $opcion ?></div>
        </li>

    <?php endforeach ?>
</ul>