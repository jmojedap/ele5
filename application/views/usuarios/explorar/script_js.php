<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var controlador = '<?php echo $controlador ?>';
    var busqueda_str = '<?php echo $busqueda_str ?>';
    var num_pagina = '<?php echo $num_pagina ?>';
    var max_pagina = '<?php echo $max_pagina ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?php echo $seleccionados_todos ?>';
    var registro_id = 0;
    var srol = '<?php echo $this->session->userdata("srol"); ?>';
        
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        
        $('#formulario_busqueda').submit(function(){
            num_pagina = 1;
            tabla_explorar();
            return false;   //Evitar envío normal del formulario
        });

        $('#tabla_resultados').on('change', '.check_registro', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            $('#seleccionados').html(seleccionados.substring(1));
        });

        $('#tabla_resultados').on('change', '#check_todos', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').prop('checked', true);
                seleccionados = seleccionados_todos;
            } else {
                //Desactivado
                $('.check_registro').prop('checked', false);
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

        $('#campo-num_pagina').change(function(){
            num_pagina = $(this).val();
            tabla_explorar();
        });
        
        $('#btn_explorar_sig').click(function()
        {
            num_pagina = Pcrn.limitar_entre(parseInt(num_pagina) + 1, 1, max_pagina);
            tabla_explorar();
        });
        
        $('#btn_explorar_ant').click(function()
        {
            num_pagina = Pcrn.limitar_entre(parseInt(num_pagina) - 1, 1, max_pagina);
            tabla_explorar();
        });

        //Específicas para Exploración de Usuarios

        //Botón, se alterna el valor del campo usuario.activo
        $('#tabla_resultados').on('click', '.alternar_activacion', function(){
            registro_id = $(this).data('usuario_id');
            alternar_activacion();
        });

        $('#tabla_resultados').on('click', '.alternar_pago', function(){
            registro_id = $(this).data('usuario_id');
            alternar_pago();
        });

        $('#tabla_resultados').on('click', '.restaurar_contrasena', function(){
            registro_id = $(this).data('usuario_id');
            restaurar_contrasena();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Actualizar la tabla explorar al cambiar de página
    function tabla_explorar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + controlador + '/tabla_explorar/' + num_pagina + '/?' + busqueda_str,
            data: $("#formulario_busqueda").serialize(),
            beforeSend: function(){
                $('#tabla_resultados').html('<div class="text-center"><i class="text-center fa fa-spinner fa-spin fa-2x"></i></div>');
            },
            success: function(response){
                act_resultados(response);
            }
        });
    }

    /**
     * Después de obtener los datos de búqueda, se actualizan los elementos
     * de la página.
     */
    function act_resultados(response)
    {
        $('#tabla_resultados').html(response.html);
        $('#head_subtitle').html(response.cant_resultados);
        $('#campo-num_pagina').val(parseInt(num_pagina));
        $('#campo-num_pagina').prop('title', parseInt(num_pagina) + ' páginas en total');

        seleccionados_todos = response.seleccionados_todos;
        num_pagina = response.num_pagina;
        max_pagina = response.max_pagina;
        seleccionados = '';
        
        history.pushState(null, null, base_url + controlador + '/explorar/' + num_pagina + '/?' + response.busqueda_str);
    }

    //AJAX - Eliminar elementos seleccionados.
    function eliminar()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + '/' + controlador + '/eliminar_seleccionados/',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(response){
                console.log(response.message);
                if ( response.status == 1 ) {
                    ocultar_eliminados();
                }
            }
        });
    }

    //Oculta las filas de los registros eliminados
    function ocultar_eliminados(){
        var arr_eliminados = seleccionados.substring(1).split('-');
        for ( key in arr_eliminados ) {
            $('#fila_' + arr_eliminados[key]).hide('slow');
            console.log('#fila_' + arr_eliminados[key]);
        }
    }

    //Cambia el valor del campo usuario.activo
    function alternar_activacion()
    {
       $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/alternar_activacion/' + registro_id,
            success: function(respuesta){
                estado = respuesta;
                act_btn_activacion(estado);
            }
       });
    }

    //Cambia el botón de activación según el resultado del cambio
    function act_btn_activacion(estado)
    {
        console.log('MODIFICANDO');
        var elemento = '#alternar_' + registro_id;
        if ( estado == 0 )
        {
            $(elemento).html('Inactivo');
            $(elemento).removeClass('btn-success');
            $(elemento).addClass('btn-warning');
        } else {
            $(elemento).html('Activo');
            $(elemento).addClass('btn-success');
            $(elemento).removeClass('btn-warning');
        }
    }

    //Cambia el valor del campo usuario.pago
    function alternar_pago()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/alternar_pago/' + registro_id,
            success: function(respuesta){
                act_btn_pago(respuesta.pago);
                act_btn_activacion(respuesta.estado);
            }
        });
    }

    //Cambia el botón de pago según el resultado del cambio
    function act_btn_pago(pago)
    {
        var elemento = '#pago_' + registro_id;
        if ( pago == 0 )
        {
            $(elemento).html('Sin pago');
            $(elemento).removeClass('btn-success');
            $(elemento).addClass('btn-warning');
        } else {
            $(elemento).html('Pagado');
            $(elemento).addClass('btn-success');
            $(elemento).removeClass('btn-warning');
        }
    }

    //Ajax, restaurar la contraseña al valor por defecto
    function restaurar_contrasena()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/restaurar_contrasena/' + registro_id,
            success: function(){
                var elemento = '#restaurar_' + registro_id;
                $(elemento).html('<i class="fa fa-check"></i> Restaurada');
                $(elemento).removeClass('btn-light');
                $(elemento).addClass('btn-info');
            }
        });
    }
    
</script>