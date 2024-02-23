<div id="construirQuizApp">
    <div class="container">
        <table class="table bg-white">
            <thead>
                <th>Texto</th>
                <th>Opciones</th>
                <th>Correcta</th>
                <th width="100px">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-form" v-on:click="setAddForm">
                        <i class="fas fa-plus"></i>
                    </button>
                </th>
            </thead>
            <tbody>
                <tr v-for="(elemento, key) in elementos">
                    <td>{{ elemento.texto }}</td>
                    <td>{{ elemento.detalle }}</td>
                    <td>{{ elemento.clave }}</td>
                    <td>
                        <button class="a4" data-toggle="modal" data-target="#modal-form" v-on:click="setCurrent(key)">
                            <i class="fas fa-pencil"></i>
                        </button>
                        <button class="a4" type="button" data-toggle="modal"
                            data-target="#delete_modal" v-on:click="setCurrent(key)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Ventana Modal Formulario -->
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <form accept-charset="utf-8" method="POST" id="construir-form" @submit.prevent="handleSubmit">
                <input type="hidden" name="id" v-bind:value="fields.id">
                <input type="hidden" name="tipo_id" value="3">
                <input type="hidden" name="quiz_id" value="<?= $row->id ?>">

                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Elemento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="contenido" class="col-md-4 col-form-label text-end text-right">Texto lectura</label>
                            <div class="col-md-8">
                                <textarea
                                    name="texto"
                                    rows="5"
                                    required
                                    class="form-control"
                                    title="Párrafo lectura"
                                    v-model="fields.texto"
                                    ></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="detalle" class="col-md-4 col-form-label text-end text-right">Opciones de respuesta</label>
                            <div class="col-md-8">
                                <textarea
                                    name="detalle" class="form-control" rows="2" required
                                    title="Opciones"
                                    v-model="fields.detalle"
                                ></textarea>
                                <small class="form-text text-muted">Opciones separadas por coma</small>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="clave" class="col-md-4 col-form-label text-end text-right">Respuesta correcta</label>
                            <div class="col-md-8">
                                <input
                                    name="clave" type="text" class="form-control" required
                                    title="Respuesta correcta"
                                    v-model="fields.clave"
                                >
                                <small class="form-text text-muted">Respuesta correcta dentro de las opciones de respuesta</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w120p">Guardar</button>
                        <button type="button" class="btn btn-secondary w120p" data-dismiss="modal">Cancelar</button>
                    </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('comunes/bs4/modal_simple_delete_v') ?>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var fieldsNew = {
    id: 0,
    texto:'',
    orden:0,
    clave:'',
    detalle:''
}

// VueApp
//-----------------------------------------------------------------------------
var construirQuizApp = new Vue({
    el: '#construirQuizApp',
    created: function(){
        //this.get_list()
    },
    data: {
        quiz: {
            id: <?= $row->id ?>
        },
        elementos: <?= $arr_elementos ?>,
        loading: false,
        fields: fieldsNew,
        currentKey: 0,
    },
    methods: {
        getList: function(){
            axios.get(URL_API + 'quices/get_elementos/' + this.quiz.id)
            .then(response => {
                this.elementos = response.data.list
            })
            .catch(function(error) { console.log(error) })
        },
        setAddForm: function(){
            this.fields = { id: 0, texto:'', orden:0,
                clave:'', detalle:''
            }
        },
        setCurrent: function(key){
            this.currentKey = key
            this.fields = this.elementos[key]
        },
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('construir-form'))
            axios.post(URL_API + 'quices/guardar_elemento/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                    this.getList()
                    this.fields = fieldsNew;
                    $('#modal-form').modal('hide')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        delete_element: function(){
            axios.get(URL_API + 'quices/delete_element/' + this.quiz.id + '/' + this.fields.id)
            .then(response => {
                if ( response.data > 0 ) toastr['info']('Elemento eliminado')
                this.getList()
            })
            .catch(function(error) { console.log(error) })
        },
    }
})
</script>