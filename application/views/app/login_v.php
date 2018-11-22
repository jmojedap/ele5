<?php $this->load->view('assets/vue'); ?>

<div class="login" id="login-form">
    <form accept-charset="utf-8" id="formulario" @submit.prevent="enviar_formulario">
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

    <div class="sep2">
        <div class="alert alert-danger" v-for="mensaje in mensajes">
            <i class="fa fa-warning"></i>
            {{ mensaje }}
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#login-form',
        data: {
            app_url: '<?php echo base_url() ?>',
            mensajes: []
        },
        methods: {
            enviar_formulario: function(){
                axios.post(this.app_url + 'app/validar_login/', $('#formulario').serialize())
                .then(response => {
                    if ( response.data.ejecutado == 0 ) {
                        this.mensajes = response.data.mensajes;
                    } else {
                        window.location = this.app_url + 'app/index'
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            }
        }
    });
</script>