<?php
    //String todos los registros de la lista actual
        $seleccionados_todos = '';
        foreach ( $resultados->result() as $row_resultado ) {
            $seleccionados_todos .= '-' . $row_resultado->id;
        }
?>

<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var busqueda_str = '<?= $busqueda_str ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?= $seleccionados_todos ?>';
    var registro_id = 0;
        
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        
        $('.check_registro').on('ifChanged', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').iCheck('check');
                seleccionados = seleccionados_todos;
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
                seleccionados = '';
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
        
        $('.sin_filtrar').hide();
        $('.b_avanzada_no').hide();
        
        $('#alternar_avanzada').click(function(){
            $('.sin_filtrar').toggle('fast');
            $('.b_avanzada_si').toggle();
            $('.b_avanzada_no').toggle();
        });
        
        
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'preguntas/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'preguntas/explorar/?' + busqueda_str;
            }
        });
    }
</script>