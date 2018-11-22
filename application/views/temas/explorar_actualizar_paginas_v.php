<script>
    //Variables
    var base_url = '<?= base_url() ?>';
    var busqueda_str = '<?= $busqueda_str ?>';
    var cant_resultados = <?= $cant_resultados ?>;
</script>

<script>
    $(document).ready(function(){
        $('#info_actualizar_paginas').hide();
        
        $('#mostrar_actualizar_paginas').click(function(){
            $(this).toggleClass('actual');
            $('#info_actualizar_paginas').toggle('fast');
        });
        
        $('#actualizar_paginas').click(function(){
            if ( cant_resultados <= 100 ) {
                actualizar_paginas();
            } else {
                var texto = '<h4 class="alert_warning">No se pueden reasociar páginas a más de 100 temas al mismo tiempo, por favor filtre los resultados.</h4>';
                $('#resultado').html(texto);
            }
            
        });
    });
</script>

<script>
    function actualizar_paginas(){
        $.ajax({
            type: 'POST',
            url: base_url + 'temas/actualizar_paginas/?' + busqueda_str,
            beforeSend : function(){
                var texto = '<h4 class="alert_info">' + 'Procesando' + '</h4>';
                $('#resultado').html(texto);
            },
            success: function(respuesta){
                var texto = '<h4 class="alert_success">' + 'Se asociaron ' + respuesta + ' páginas' + '</h4>';
                $('#resultado').html(texto);
            }
        });
    }
</script>

<span class="a2" id="mostrar_actualizar_paginas" title="Asociar imágenes con nombre que coincidan con el código de los temas">
    <i class="fa fa-refresh"></i> Reasociar páginas
</span>

<div class="div2" id="info_actualizar_paginas">
    <hr/>
    <h4>Actualización de páginas (<?= $cant_resultados ?> temas)</h4>
    <p>
        Asociar imágenes con nombre que coincidan con el código de los temas. Se <span class="resaltar">eliminarán</span> las páginas que actualmente
        estén asociadas a los temas seleccionados.
    </p>
    <span class="button orange" id="actualizar_paginas">Iniciar</span>
    
    <div class="div3" id="resultado">
    </div>
    
    <hr/>
</div>

