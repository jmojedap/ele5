<?php
    $cant_elementos = $elementos->num_rows() - 1;
    $key_elemento = 0;
    
    //Imagen principal
    $att_img = array(
        'class' =>  'principal'
    );
    
    //Array elementos
    $array_elementos = array();
    foreach ( $elementos->result() as $row_elemento ) {
        $array_elementos[] = $row_elemento->orden . '|' . $row_elemento->texto;
    }
    
    shuffle($array_elementos);
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
        
        $('#enviar').click(function(){
            actualizar_resultado();
            guardar_resultado();
        });
        
        $( "#sortable" ).sortable({
            update: function(){
                respuesta_arr = $(this).sortable('toArray', { attribute: 'data-orden' });
                respuesta = JSON.stringify(respuesta_arr);
                respuesta = respuesta.replace(new RegExp('"', 'g'), '');
            }
        });
        
        $( "#sortable" ).disableSelection();
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
  
<style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.0em; cursor: move; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
</style>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<?php if ( strlen($imagen['src']) > 0 ){ ?>
    <div class="div2" style="text-align: center;">
        <?php $att_img['src'] = $imagen['src'] ?>
        <?= img($att_img) ?>
    </div>
<?php } ?>

<p id="resultado"></p>

<div class="div2">
    <ul id="sortable" class="opciones_quiz">
        <?php foreach ($array_elementos as $str_elemento) : ?>
            <?php
                $array_elemento = explode('|', $str_elemento);
                $orden = $array_elemento[0];
                $texto = $array_elemento[1];     
            ?>
            <li class="ui-state-default" data-orden="<?= $orden ?>">
                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <?= $texto ?>
            </li>
        <?php endforeach ?>
        
    </ul>
</div>