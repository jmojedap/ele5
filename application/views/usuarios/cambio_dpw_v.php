<div id="change_password">
    <div class="start_content" class="text-center">
                
        <h3 class="text-center"><?= $this->session->userdata('nombre_completo'); ?></h3>
        
        <div class="alert alert-info text-center">
            <i class="fa fa-lock fa-2x"></i>
            <p>  
                Usted tiene actualmente la contraseña por defecto. Para continuar debe cambiarla.
            </p>
        </div>

        <form accept-charset="utf-8" method="POST" id="change_password_form" @submit.prevent="send_form">

            <div class="form-group">
                <input
                    type="password" name="password" class="form-control"
                    placeholder="nueva contraseña" title="Debe tener al menos 8 caracteres"
                    required autofocus pattern=".{8,}"
                    v-model="password" v-on:change="check_match"
                    >
            </div>
            <div class="form-group">
                <input
                    type="password" name="passconf"
                    class="form-control"
                    required minlength="8"
                    placeholder="confirme su nueva contraseña" title="confirme su nueva contraseña"
                    v-model="passconf" v-on:change="check_match" v-bind:class="{'is-invalid': passwords_match == 0, 'is-valid': passwords_match == 1 }"
                    >
                <div class="invalid-feedback">
                    La contraseña de confirmación no coincide con la primera
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Cambiar contraseña</button>
            </div>
        
            <div class="form-group">
                <a href="<?php echo base_url('app/logout') ?>" class="btn btn-block btn-warning" title="Cancelar">Cancelar</a>
            </div>

        </form>

        <div class="clearfix"></div>

        <div id="errors">
            <div class="alert alert-danger" v-show="passwords_match == 0">
                
            </div>
            <div class="alert alert-danger" v-for="(error, error_key) in errors">
                {{ error }}
            </div>
        </div>
        
    </div>

</div>

<script>
    new Vue({
        el: '#change_password',
        created: function(){
            //this.get_list();
        },
        data: {
            password: '',
            passconf: '',
            passwords_match: -1,
            validated: -1,
            errors: [],
        },
        methods: {
            send_form: function(){
                console.log('send form', this.validated);
                if ( this.validated == 1 )
                {
                    axios.post(url_api + 'usuarios/cambiar_dpw/', $('#change_password_form').serialize())
                    .then(response => {
                        if ( response.data.status == 1 ) {
                            toastr['success']('La contraseña fue cambiada')
                            setTimeout(() => {
                                window.location = url_app + 'app/index/';
                            }, 2000);
                        } else {
                            toastr['error']('La contraseña no se cambió')
                            this.errors = response.data.errors
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            check_match: function(){
                if ( this.passconf.length > 0 )
                {
                    if ( this.password == this.passconf ) {
                        this.passwords_match = 1
                        this.validated = 1
                    } else {
                        this.passwords_match = 0
                        this.validated = 0
                    }
                }
                console.log('check_match', this.passwords_match);
            },
        }
    });
</script>