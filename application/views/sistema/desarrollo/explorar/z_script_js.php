<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var controlador = '<?= $controlador ?>';
    var num_pagina = '<?= $num_pagina ?>';
    var num_pagina_ir = 0;
    var max_pagina = '<?php echo $max_pagina ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?php echo $seleccionados_todos ?>';
    var registro_id = 0;
        
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {   
        $('#alternar_avanzada').click(function(){
            $('.filtro').toggle('fast');
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Elimina los registros seleccionados
    function eliminar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + '/' + controlador + '/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(eliminados){
                ocultar_eliminados(eliminados);
            }
        });
    }
    
    //Al eliminar registros, ocultar de la tabla las filas eliminadas
    function ocultar_eliminados(eliminados)
    {
        var cant_eliminados = 0;
        for (var i in eliminados) 
        {
            $('#fila_' + eliminados[i]).hide();
            console.log(eliminados[i]);
            if ( eliminados[i] > 1 ) { cant_eliminados++; }
        }
        toastr['info'](cant_eliminados + ' usuarios eliminados');
    }
</script>