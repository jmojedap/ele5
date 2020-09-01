<script>
// Variables
//-----------------------------------------------------------------------------
    var conversacion_id = <?= $row->id ?>;
    var usuario_id = 0;
    var nombre_usuario = '';
    var num_usuarios = <?= $usuarios->num_rows(); ?>;
    var num_mensajes = <?= $mensajes->num_rows(); ?>;

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        
        activacion_elementos();
        
        //Si hay mensajes, ir al final
        if ( num_mensajes > 0 ) {
            $('#mensajes').scrollTop($('#mensajes')[0].scrollHeight);
        }
        
        $('#casilla_url').hide();
        
        $('#mostrar_usuarios').click(function(){
            $('#lista_usuarios').show('fast');
            $('#ocultar_usuarios').show();
            $(this).hide();
        });
        
        $('#ocultar_usuarios').click(function(){
            $('#lista_usuarios').hide('fast');
            $('#mostrar_usuarios').show();
            $(this).hide();
        });
        
        $('#mostrar_q_usuarios').click(function(){
            $('#q_usuarios').toggleClass('hide');
            $('#q_usuarios').focus();
        });
        
        if ( num_usuarios > 5 ) {
            $('#lista_usuarios').hide();
            $('#mostrar_usuarios').show();
            $('#ocultar_usuarios').hide();
        } else {
            $('#mostrar_usuarios').hide();
        }
        
        $('#mostrar_url').click(function(){
            $('#casilla_url').toggle('fast');
        });
        
        /**
         * Quitar usuarios
         * Se utiliza .on() para que el evento también se asocie a los elementos
         * nuevos dentro del elemento #lista_usuarios
         */
        $('#lista_usuarios').on("click", '.quitar_usuario', function(){
            usuario_id = $(this).data('usuario_id');
            quitar_usuario();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    $(function() {
        
        $('#q_usuarios').typeahead({
            ajax: {
                url: url_app + 'mensajes/usuarios_agregables/' + conversacion_id,
                method: 'post',
                triggerLength: 2
            },
            onSelect: agregar_usuario
        });
    });

    //Ajax
    function agregar_usuario(item)
    {
        usuario_id = item.value;
        nombre_usuario = item.text;
    
        $.ajax({        
            type: 'POST',
            url: url_app + 'mensajes/agregar_usuario/',
            data: {
                conversacion_id : conversacion_id,
                usuario_id : usuario_id
            },
            success: function(){
                mostrar_agregado();
                $('#q_usuarios').val('');
                num_usuarios++;
                activacion_elementos();
            }
        });
    }
    
    function quitar_usuario()
    {
       $.ajax({
            type: 'POST',
            url: url_app + 'mensajes/quitar_usuario/',
            data: {
                conversacion_id : conversacion_id,
                usuario_id : usuario_id
            },
            success: function(){
                $('#usuario_' + usuario_id).hide();
                num_usuarios--;
                activacion_elementos();
            }
        });
    }
    
    function mostrar_agregado()
    {
        var boton_quitar = '<i class="fa fa-times link_menor quitar_usuario" title="Quitar al usuario de la conversación" data-usuario_id="' + usuario_id + '"></i>';
        $('#lista_usuarios').append('<span class="removible" id="usuario_' + usuario_id + '">' + nombre_usuario + ' ' + boton_quitar + '</span>');
    }
    
    function activacion_elementos()
    {
        if ( num_usuarios < 2  ) {
            $('#texto_mensaje').attr('disabled', 'disabled');
            $('#asunto').attr('disabled', 'disabled');
            $('#url').attr('disabled', 'disabled');
            $('#boton_enviar').hide();
            $('#mostrar_url').hide();
        } else {
            $('#texto_mensaje').removeAttr('disabled');
            $('#asunto').removeAttr('disabled');
            $('#url').removeAttr('disabled');
            $('#boton_enviar').show();
            $('#mostrar_url').show();
        }
    }
</script>