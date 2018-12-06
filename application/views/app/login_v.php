<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(function(){
        $('#formulario_login').submit(function(){
            validar_login();
            return false;
        });
    });

// Funciones
//-----------------------------------------------------------------------------
    function validar_login(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'app/validar_login',
            data: $('#formulario_login').serialize(),
            success: function(response){
                //console.log(response.mensajes);
                if ( response.ejecutado ) {
                    window.location = base_url + 'app/index/';
                } else {
                    mostrar_mensajes(response.mensajes);
                }
            }
        });
    }

    function mostrar_mensajes(mensajes)
    {
        $('#mensajes').html('');

        for (i in mensajes) {
            console.log(mensajes[i]);
            var componente = '<div class="alert alert-danger">' + mensajes[i] + '</div>'
            $('#mensajes').append(componente);
        }
    }
</script>

<div class="login">
    <form accept-charset="utf-8" id="formulario_login" method="post">
        <div class="form-group">
            <input
                type="text"
                name="username"
                value=""
                id="username"
                class="form-control login"
                required="required"
                autofocus="1"
                title="Escriba su nombre de usuario"
                placeholder="usuario">
        </div>
        
        <div class="form-group">
            <input
                type="password"
                name="password"
                value="" 
                id="password"
                class="form-control login"
                required="required"
                title="Escriba su contraseña"
                placeholder="contraseña"
                >
        </div>
        <div class="">
            <input type="submit" value="Ingresar" class="btn btn-success btn-block">
        </div>
    </form>

    <div class="clearfix"></div>

    <div class="sep2" id="mensajes"></div>
</div>