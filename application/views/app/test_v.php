<div id="passwords_app">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="pw_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="username" class="col-md-4 col-form-label text-right">username</label>
                    <div class="col-md-8">
                        <input
                            name="username" type="text" class="form-control"
                            required
                            title="username" placeholder="username"
                            v-model="form_values.username"
                        >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-right">Contraseña</label>
                    <div class="col-md-8">
                        <input
                            name="password" type="text" class="form-control"
                            required
                            v-model="form_values.password"
                        >
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-success w120p" type="submit">Enviar</button>
                    </div>
                </div>
            </form>
            <hr>
            <pre>{{ respuesta }}</pre>
        </div>
    </div>
</div>

<script>
var passwords_app = new Vue({
    el: '#passwords_app',
    created: function(){
        //this.get_list()
    },
    data: {
        respuesta: 'SIN ENVIAR',
        form_values: {
            username: 'jmojedap',
            password: ''
        }
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'usuarios/pruebas_pw/', $('#pw_form').serialize())
            .then(response => {
                this.respuesta = response.data
            }).catch(function(error) {console.log(error)})
        },
    }
})
</script>