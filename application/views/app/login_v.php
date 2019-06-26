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
            beforeSend: function(){
                $('#mensajes').html('');
                $('#loading_icon').show();
                $('#field-username').prop('disabled', true);
                $('#field-password').prop('disabled', true);
                $('#submit_button').prop('disabled', true);
            },
            success: function(response){
                console.log(response);
                //console.log(response.mensajes);
                if ( response.status ) {
                    window.location = base_url + 'app/index/?dpw=' + response.tiene_dpw;
                } else {
                    $('#loading_icon').hide();
                    mostrar_mensajes(response.mensajes);
                    $('#field-username').prop('disabled', false);
                    $('#field-password').prop('disabled', false);
                    $('#submit_button').prop('disabled', false);
                }
            }
        });
    }

    function mostrar_mensajes(mensajes)
    {
        $('#mensajes').html('');

        for (i in mensajes)
        {
            console.log(mensajes[i]);
            var componente = '<div class="alert alert-danger">' + mensajes[i] + '</div>'
            $('#mensajes').append(componente);
        }
    }
</script>

<div class="start_content">
    <form accept-charset="utf-8" id="formulario_login" method="post">
        <div class="form-group">
            <input
                id="field-username"
                type="text"
                name="username"
                value=""
                class="form-control form-control-lg"
                required="required"
                autofocus="1"
                title="Escriba su nombre de usuario"
                placeholder="usuario">
        </div>
        
        <div class="form-group">
            <input
                id="field-password"
                type="password"
                name="password"
                value="" 
                class="form-control form-control-lg"
                required="required"
                title="Escriba su contraseña"
                placeholder="contraseña"
                >
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-block btn-lg" type="submit" id="submit_button">
                Entrar
            </button>
        </div>
        <div class="text-center" id="loading_icon" style="display: none;">
            <i class="fa fa-spinner fa-spin fa-3x"></i>
        </div>
    </form>

    <div class="clearfix"></div>

    <div class="mb-2" id="mensajes"></div>
</div>