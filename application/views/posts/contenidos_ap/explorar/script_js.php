<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var controlador = '<?= $controlador ?>';
    var busqueda_str = '<?= $busqueda_str ?>';
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
        //Al cambiar la casilla de todos los elementos de la tabla
        $('#tabla_resultados').on('change', '#check_todos', function(){
            
            $('.check_registro').prop('checked', $(this).prop('checked'));
            
            if ( $(this).prop('checked') ) 
            {
                seleccionados = seleccionados_todos;
            } else {
                seleccionados = '';
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
        });
        
        //Al cambiar la casilla un elemento de la tabla
        $('#tabla_resultados').on('change', '.check_registro', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).prop('checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
        });
        
        
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
        
        $('#alternar_avanzada').click(function(){
            $('.filtro').toggle('fast');
        });
        
        $('#campo-num_pagina').change(function(){
            num_pagina_ir = $(this).val() - 1;
            tabla_explorar();
        });
        
        $('#btn_explorar_sig').click(function()
        {
            num_pagina_ir = Pcrn.limitar_entre(parseInt(num_pagina) + 1, 0, max_pagina);
            tabla_explorar();
        });
        
        $('#btn_explorar_ant').click(function()
        {
            num_pagina_ir = Pcrn.limitar_entre(parseInt(num_pagina) - 1, 0, max_pagina);
            tabla_explorar();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Actualizar la tabla explorar al cambiar de página
    function tabla_explorar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + '/' + controlador + '/ap_tabla_explorar/' + num_pagina_ir + '/?' + busqueda_str,
            data: $("#formulario_busqueda").serialize(),
            success: function(respuesta){
                $('#tabla_resultados').html(respuesta.html);
                seleccionados_todos = respuesta.seleccionados_todos;
                num_pagina = respuesta.num_pagina;
                seleccionados = '';
                $('#campo-num_pagina').val(parseInt(num_pagina) + parseInt(1));
            }
        });
    }

    //Elimina los registros seleccionados
    function eliminar()
    {
     $.ajax({
        type: 'POST',
        url: base_url + '/' + controlador + '/delete_selected',
        data: {
                selected : seleccionados.substring(1)
            },
            success: function(data){
                ocultar_eliminados(data['qty_deleted']);
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
        toastr['info'](cant_eliminados + ' posts eliminados');
    }
</script>