<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var controlador = '<?= $controlador ?>';
    var redirect = '<?= base_url("{$cf}/?{$busqueda_str}") ?>';
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
        
        $('.filtro').hide();
        
        $('#alternar_avanzada').click(function(){
            $('.filtro').toggle('fast');
        });
        
        
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + '/' + controlador + '/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = redirect;
            }
        });
    }
</script>