<div id="recovery_app" class="text-center center_box_450">

    <div v-show="app_status == 'start'">
        <p>
            Escriba su correo electrónico.
            Enviaremos un link para asignar una nueva contraseña.
        </p>        

        <form id="app_form" @submit.prevent="send_form" >

            <div class="form-group">
                <label class="sr-only" for="email">Correo electrónico</label>
                <input
                    name="email" type="email" class="form-control" required
                    placeholder="Correo electrónico" title="Escriba su correo electrónico" v-model="email"
                    >
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Enviar</button>
            </div>
        </form>
    </div>

    <div v-show="app_status == 'no_user'">
        <a href="<?= base_url("usuarios/restaurar") ?>" class="btn btn-light mb-2">
            <i class="fa fa-arrow-left"></i> Atrás
        </a>
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-user-slash"></i>
            <br/>
            No hay ningún usuario registrado con el correo: <b>{{ email }}</b>.
        </div>
    </div>


    <div class="card" style="margin-bottom: 10px;" v-show="app_status == 'sent'">
        <div class="card-body">
            <i class="fa fa-check fa-2x text-success"></i>
            <p>
                Enviamos un link al correo electróico <strong class="text-success">{{ email }}</strong> para reestablecer su contraseña.
            <p>
            <p>Recuerde revisar la carpeta de correo no deseado.</p>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#recovery_app',
        data: {
            email: '',
            app_status: 'start'
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'usuarios/recovery_email/', $('#app_form').serialize())
                .then(response => {
                    console.log(response.data.status);
                    if ( response.data.status == 1 ) {
                        this.no_user = false;
                        this.app_status = 'sent';
                    } else if ( response.data.status == 0 ) {
                        this.app_status = 'no_user';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>
