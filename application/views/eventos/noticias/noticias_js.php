<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var url_mas = '<?= $url_mas ?>';
    var evento_id = 0;
    var limit = <?= $limit ?>;
    var offset = <?= $limit ?>;
    var busqueda_str = '<?= $busqueda_str ?>';
    
//DOCUMENT
//---------------------------------------------------------------------------------------------------
    
    $(document).ready(function(){
        $('.eliminar_noticia').click(function(){
            evento_id = $(this).data('evento_id');
            eliminar_evento();
        });
        
        $('#mas_noticias').click(function(){
            //alert(offset);
            mas_noticias();
        });
    });
    
//FUNCIONES
//---------------------------------------------------------------------------------------------------
    
    //Ajax
    function eliminar_evento()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'eventos/eliminar/' + evento_id,
            success: function(){
                ocultar_noticia();
            }
        });
    }
    
    //Ajax
    function mas_noticias()
    {
        $.ajax({        
            type: 'POST',
            url: url_mas + limit + '/' + offset + '/?' + busqueda_str,
            success: function(respuesta){
                offset += limit;
                mostrar_mas_noticias(respuesta);
                
            }
        });
    }
    
    function ocultar_noticia()
    {
        $('#ev_' + evento_id).slideUp('slow');
    }
    
    function mostrar_mas_noticias(respuesta)
    {
        if ( respuesta.cant_noticias > 0 ) {
            $('#listado_noticias').append(respuesta.html);
            altura = $('.main_nav_col').height();
            altura_nueva = altura + (175 * respuesta.cant_noticias);
            $('.main_nav_col').attr('style', 'height: ' + altura_nueva + 'px');
        } else {
            $('#no_mas_noticias').show();
            $('#mas_noticias').hide();
        }
    }
    
</script>