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

    <a href="<?= base_url("orders/pays") ?>" class="btn btn-warning btn-block btn-lg" style="margin-bottom: 10px;">
        PAGOS
    </a>

    <div class="alert alert-success text-center">
        <p>
            ENLINEA EDITORES se complace en presentar su nuevo servicio:
        </p>
        <p style="font-size: 1.5em;">
            <strong><i class="fa fa-check"></i> PAGO EN LÍNEA</strong>
        </p>

        <p>
            Ahora usted podrá adquirir nuestros productos desde la comodidad de su casa.
        </p>

        <p classs="text-left">
            Realice la compra SOLO por una de estas dos modalidades:
        </p>
        <ul class="text-left">
            <li>
                <strong>CÓDIGO DE LA INSTITUCIÓN</strong>:
                El colegio entrega el CÓDIGO DE LA INSTITUCIÓN para que usted pueda realizar el pago.
            </li>
            <li>
                <strong>CÓDIGO DE USUARIO</strong>:
                Algunos colegios asignan al estudiante un CÓDIGO DE USUARIO, con este se
                ingresa directamente a la Plataforma En Línea y se realiza
                el pago. La institución o el Director de Grupo se lo harán
                llegar.
            </li>
        </ul>
 
    </div>

    <div class="mb-2" id="mensajes"></div>
</div>