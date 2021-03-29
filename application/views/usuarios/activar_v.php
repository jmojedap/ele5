<?php
    //Textos
        $textos['subtitulo'] = 'Activación de cuenta';
        $textos['boton'] = 'Activar mi cuenta';
        
        if ( $tipo_activacion == 'restaurar' ) {
            $textos['subtitulo'] = 'Restauración de contraseña';
            $textos['boton'] = 'Guardar';
        }
?>

<div id="password_app">
    <div class="text-center">
        <h4 class="text-primary"><?= $row->nombre . ' ' . $row->apellidos ?></h4>
        <p class="text-muted"><?= $row->username ?></p>
        <p>Establezca su contraseña</p>

        <form accept-charset="utf-8" method="POST" id="password_form" @submit.prevent="send_form">
            <div class="form-group">
                <div class="input-group mb-3">
                    <input
                        name="password" v-bind:type="pw_type" autofocus
                        class="form-control form-control-lg"
                        required pattern=".{8,}"
                        title="Debe tener al menos 8 caracteres"
                        >
                    <div class="input-group-append">
                        <button class="btn btn-light" type="button" v-on:click="toggle_password">
                            <i class="far fa-eye-slash" v-show="pw_type == 'password'"></i>
                            <i class="far fa-eye" v-show="pw_type == 'text'"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-lg btn-block" type="submit">
                    <?= $textos['boton'] ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    new Vue({
        el: '#password_app',
        data: {
            cod_activacion: '<?= $cod_activacion ?>',
            pw_type: 'password',
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'usuarios/establecer_contrasena/' + this.cod_activacion, $('#password_form').serialize())
                .then(response => {
                    console.log(response.data);
                    if ( response.data.status == 1 ) {
                        window.location = url_app   
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            toggle_password: function(){
                if ( this.pw_type == 'text' )
                {
                    this.pw_type = 'password'
                } else {
                    this.pw_type = 'text'
                }
            },
        }
    });
</script>

