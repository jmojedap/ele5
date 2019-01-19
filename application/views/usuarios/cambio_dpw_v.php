<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(function(){
        $('#formulario_cambio_dpw').submit(function(){
            cambiar_dpw();
            return false;
        });
    });

// Funciones
//-----------------------------------------------------------------------------
    function cambiar_dpw(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/cambiar_dpw',
            data: $('#formulario_cambio_dpw').serialize(),
            success: function(response){
                console.log(response);
                //console.log(response.mensajes);
                if ( response.ejecutado ) {
                    window.location = base_url + 'app/index/';
                } else {
                    mostrar_mensajes(response.mensajes);
                    limpiar_form();
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

    function limpiar_form()
    {
        $('#field-password').val('');
        $('#field-passconf').val('');
    }
</script>

<div class="start_content" class="text-center">
            
    <h3 class="text-center"><?= $this->session->userdata('nombre_completo'); ?></h3>
    
    <div class="alert alert-info text-center">
        <i class="fa fa-lock fa-2x"></i>
        <p>  
            Usted tiene actualmente la contraseña por defecto. Para continuar debe cambiarla.
        </p>
    </div>
    

    <form accept-charset="utf-8" id="formulario_cambio_dpw" method="post">

        <div class="form-group">
            <input
                type="password"
                id="field-password"
                name="password"
                class="form-control"
                placeholder="nueva contraseña"
                required
                autofocus
                pattern=".{8,}"
                title="Debe tener al menos 8 caracteres"
                >
        </div>
        <div class="form-group">
            <input
                type="password"
                id="field-passconf"
                name="passconf"
                class="form-control"
                required
                placeholder="confirme su nueva contraseña"
                title="confirme su nueva contraseña"
                minlength="8"
                >
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-block">Cambiar contraseña</button>
        </div>
    
        <div class="form-group">
            <a href="<?php echo base_url('app/logout') ?>" class="btn btn-block btn-warning" title="Cancelar">Cancelar</a>
        </div>

    </form>

    <div class="clearfix"></div>

    <div class="sep2" id="mensajes"></div>
    
</div>

