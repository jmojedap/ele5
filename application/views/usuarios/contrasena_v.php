<?php

    $password_actual = array(
        'name'  =>  'password_actual',
        'id'    =>  'password_actual',
        'class' =>  'form-control',
        'required'  => TRUE,
        'autofocus'  => TRUE,
        'title'  => 'Escriba su contraseña actual',
        'placeholder' =>   'Escriba su contraseña actual'
    );
    
    $password = array(
        'name'  =>  'password',
        'id'    =>  'password',
        'class' =>  'form-control',
        'required'  => TRUE,
        'placeholder' =>   'Escriba su nueva contraseña',
        'pattern' => '.{8,}',   //Al menos 8 caracteres
        'title' => 'Debe tener al menos 8 caractéres'
    );
    
    $passconf = array(
        'name'  =>  'passconf',
        'id'    =>  'passconf',
        'class' =>  'form-control',
        'required'  => TRUE,
        'placeholder' =>   'Confirme la nueva contraseña',
        'pattern' => '.{8,}',
        'title' => 'Confirme la nueva contraseña'
    );
    
    $submit = array(
        'value' =>  'Guardar',
        'class' =>  'btn btn-primary btn-block'
    )

?>

<div>
    <div class="card" style="max-width: 500px; margin: 0px auto;">
        <div class="card-header">
            Cambio de contraseña
        </div>
        <div class="card-body">
            <form accept-charset="utf-8" id="password_form" method="post">
                <?= form_hidden('id', $usuario_id_cambio); ?>
                <div class="form-group">
                    <?= form_password($password_actual); ?>    
                </div>

                <div class="form-group">
                    <?= form_password($password); ?>
                </div>

                <div class="form-group">
                    <?= form_password($passconf); ?>
                </div>

                <div class="form-group">
                    <?= form_submit($submit) ?>        
                </div>

                <div class="alert alert-success" role="alert" id="mensaje_exito" style="display: none;">
                    <i class="fa fa-check"></i>
                    La contraseña fue cambiada exitosamente.
                </div>
                <div class="alert alert-danger" role="alert" id="mensaje_error" style="display: none;">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span id="texto_error"></span>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';

// Document ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#password_form').submit(function(){
            cambiar_contrasena();
            
            return false;
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    function cambiar_contrasena(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/contrasena_e',
            data: $('#password_form').serialize(),
            success: function(response){
                console.log(response.mensaje);
                if ( response.ejecutado ) {
                    $('#mensaje_exito').show() 
                    $('#mensaje_error').hide() 
                } else {
                    $('#texto_error').html(response.mensaje) 
                    $('#mensaje_error').show() 
                }
                limpiar_formulario();
            }
        });
    }

    function limpiar_formulario(){
        $('#password_actual').val('');
        $('#password').val('');
        $('#passconf').val('');
    }
</script>