<div id="procesos_app">
    <div class="">
        <div class="row">
            <div class="col-md-4">
                <table class="table bg-white">
                    <thead>
                        <th width="10px"></th>
                        <th>Proceso</th>
                    </thead>
                    <tbody>
                        <tr v-for="(proceso, key) in procesos" v-bind:class="{'table-info': key == curr_key }">
                            <td>
                                <button class="btn btn-sm btn-light" v-on:click="set_proceso(key)">
                                    <span v-show="key == curr_key"><i class="fa fa-check-square"></i></span>
                                    <span v-show="key != curr_key"><i class="far fa-square"></i></span>
                                </button>
                            </td>
                            <td>{{ proceso.nombre_proceso }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8">
                <div class="card mw750p">
                    <div class="card-body">
                        <h3>{{ curr_proceso.nombre_proceso }}</h3>
                        <div class="mb-2" v-html="curr_proceso.contenido"></div>
                        <div class="mb-2">
                            <button class="btn btn-primary btn-lg" v-on:click="ejecutar_proceso">
                                EJECUTAR
                            </button>
                        </div>
                        <div class="alert" v-bind:class="resultado.clase" v-show="resultado.mensaje.length > 0">
                            <i class="fa fa-spin fa-spinner mr-1" v-show="loading"></i>
                            <i class="fa fa-check mr-1" v-show="resultado.status == 1"></i>
                            <span v-html="resultado.mensaje"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var procesos_app = new Vue({
    el: '#procesos_app',
    created: function(){
        this.set_proceso(3)
    },
    data: {
        procesos: <?= json_encode($procesos->result()) ?>,
        curr_proceso: [],
        curr_key: 0,
        loading: false,
        resultado: {
            status: -1,
            clase: 'alert-info',
            mensaje: ''
        },
    },
    methods: {
        set_proceso: function(key_proceso){
            this.curr_key = key_proceso
            this.curr_proceso = this.procesos[key_proceso]
            this.reiniciar_resultado()
        },
        ejecutar_proceso: function(){
            this.loading = true
            this.reiniciar_resultado()
            this.resultado.mensaje = 'Ejecutando'
            var url_proceso = '<?= base_url() ?>' + this.curr_proceso.link_proceso
            console.log(url_proceso)
            axios.get(url_proceso)
            .then(response => {
                console.log(response.data)
                if ( response.data.status == 1 ) {
                    this.resultado.clase = 'alert-success'
                    this.resultado.status = 1
                }
                this.resultado.mensaje = response.data.message
                this.loading = false
            }).catch(function(error) { console.log(error) })
        },
        reiniciar_resultado: function(){
            this.resultado = {
                status: -1,
                clase: 'alert-info',
                mensaje: ''
            }
        },
    }
})
</script>