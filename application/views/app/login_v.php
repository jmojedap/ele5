<div id="loginApp">
    <form accept-charset="utf-8" method="POST" id="loginForm" @submit.prevent="handleSubmit">
        <fieldset v-bind:disabled="loading">
            <div class="mb-3">
                <input
                    type="text" name="username"
                    value=""
                    class="form-control form-control-lg"
                    required="required" autofocus="1"
                    title="Escriba su nombre de usuario" placeholder="usuario"
                    >
            </div>
            
            <div class="mb-3">
                <input
                    type="password" name="password" value="" class="form-control form-control-lg"
                    required="required" title="Escriba su contraseña" placeholder="contraseña"
                    >
            </div>
            <div class="mb-3">
                <button class="btn btn-success btn-block btn-lg" type="submit" id="submit_button">
                    <i v-show="loading" class="fa fa-spinner fa-spin"></i>
                    Entrar
                </button>
            </div>
        <fieldset>
        <div>
            <div class="alert alert-danger" v-for="mensaje in mensajes">
                {{ mensaje }}
            </div>
        </div>
        <div class="mb-3">
            <a href="<?= base_url("orders/pays") ?>" class="btn btn-info btn-block btn-lg" style="margin-bottom: 10px;">
                PAGOS
            </a>
        </div> 
    </form>
</div>

<script>
var loginApp = new Vue({
    el: '#loginApp',
    data: {
        loading: false,
        mensajes: []
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('loginForm'))
            axios.post(url_api + 'app/validar_login/', formValues)
            .then(response => {
                if ( response.data.status == 1 ) {
                    window.location = url_app + 'app/index/?dpw=' + response.data.tiene_dpw;
                } else {
                    this.mensajes = response.data.mensajes
                    this.loading = false
                }
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>