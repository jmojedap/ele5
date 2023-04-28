<?php
    $seleccionados_todos = '';
    foreach ( $resultados->result() as $row_resultado ) {
        $seleccionados_todos .= '-' . $row_resultado->id;
    }
?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var busqueda_str = '<?= $busqueda_str ?>';
        var seleccionados = '';
        var seleccionados_todos = '<?= $seleccionados_todos ?>';
        var registro_id = 0;
        
        var kit_id = 0;
        var inactivo = 0;
        
// Document
//-----------------------------------------------------------------------------

    $(document).ready(function(){

        $("#check_todos").change(function() {
            $(".check_registro").prop("checked", $(this).prop("checked"));
            if ($(this).prop("checked")) {
                seleccionados = seleccionados_todos;
            } else {
                seleccionados = '';
            }
            console.log(seleccionados)
        });

        $('.check_registro').change(function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            console.log(seleccionados)
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'kits/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'kits/explorar/?' + busqueda_str;
            }
        });
    }
    
    
</script>