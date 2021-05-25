<div id="construir_app">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-2">
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="construir_form" @submit.prevent="send_form">
                        <fieldset v-bind:disabled="loading">
                            <div class="form-group">
                                <label for="texto">Pregunta</label>
                                <textarea
                                    id="field-texto" name="texto" class="form-control" rows="4"
                                    required
                                    title="Pregunta" placeholder="Escriba aquí la pregunta o enunciado"
                                    v-model="form_values.texto"
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="detalle">Respuestas correctas</label>
                                <input
                                    type="text" name="detalle" class="form-control"
                                    required
                                    title="Respuesta" placeholder=""
                                    v-model="form_values.detalle"
                                >
                                <span class="form-text text-info">
                                    Entre comillas dobles y separadas por coma. Ejemplo: "1.5", "1.50", "Uno y medio"
                                </span>
                            </div>
                            
                            <div class="form-group">
                                <button class="btn w120p" type="submit" v-bind:class="{'btn-success': element_id == 0, 'btn-primary': element_id > 0 }">
                                    <span v-show="element_id > 0">Guardar</span>
                                    <span v-show="element_id == 0">Agregar</span>
                                </button>
                                <button class="btn btn-secondary w120p" type="button" v-show="element_id > 0" v-on:click="clear_form">
                                    Cancelar
                                </button>
                            </div>
                        <fieldset>
                    </form>
                </div>
            </div>

            <div class="border-top p-2 w100pc" v-show="imagen.id">
                <h4>Imagen principal</h4>
                <div class="mb-2">
                    <button class="btn btn-warning" data-toggle="modal" data-target="#delete_image_modal">
                        Eliminar
                    </button>
                </div>

                <img
                    v-bind:src="imagen.src"
                    class="border w100pc"
                    alt="imagen quiz"
                    onerror="this.src='<?= URL_IMG ?>app/img_pregunta_nd.png'"
                >
            </div>

            <div class="border-top p-2" v-show="!imagen.id">
                <h4>Cargar imagen</h4>
                <?php $this->load->view('common/upload_file_form_v') ?>
            </div>
        </div>
        <div class="col-md-8">
            <table class="table bg-white">
                <tbody>
                    <tr v-for="(element, ek) in elements" v-bind:class="{'table-info': ek == key_element }">
                        <td>
                            <p>
                                <span class="etiqueta informacion">{{ parseInt(element.orden) + 1 }}</span>
                                <span class="etiqueta exito">{{ element.detalle }}</span>
                            </p>

                            <p v-html="element.texto"></p>
                        </td>
                        <td width="90px">
                            <button class="a4 editar_elemento" type="button" title="Editar elemento" v-on:click="set_current(ek)">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                            <button class="a4 eliminar_elemento" type="button" title="Eliminar elemento" v-on:click="set_current(ek)" data-toggle="modal" data-target="#delete_modal">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-info w120p" v-on:click="clear_form">
                                Nuevo
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php $this->load->view('common/modal_single_delete_v') ?>
    <?php $this->load->view('quices/construir_v2/modal_delete_image_v') ?>

</div>

<script>
var construir_app = new Vue({
    el: '#construir_app',
    created: function(){
        //this.get_list()
    },
    data: {
        form_values: {
            texto: '',
            detalle: '',
            orden: <?= $elementos_quiz->num_rows() ?>
        },
        elements: <?= json_encode($elementos_quiz->result()) ?>,
        key_element: -1,
        element_id: 0,
        quiz: <?= json_encode($row) ?>,
        imagen: <?= json_encode($imagen) ?>,
        file: '',   //Para proceso de upload
        loading: false,
        loading_file: false,
    },
    methods: {
        set_current: function(key){
            this.key_element = key
            this.form_values.texto = this.elements[key].texto
            this.form_values.detalle = this.elements[key].detalle
            this.form_values.orden = this.elements[key].orden
            this.element_id = this.elements[key].id
            $('#field-texto').focus()
        },
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('construir_form'))
            form_data.append('id', this.element_id)
            form_data.append('quiz_id', this.quiz.id)
            form_data.append('tipo_id', 3)
            form_data.append('orden', this.form_values.orden)
            form_data.append('clave', 1)
            axios.post(url_api + 'quices/save_element/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    this.get_elements()
                    toastr['success']('Guardado')
                }
            })
            .catch( function(error) {console.log(error)} )
        },
        get_elements: function(){
            this.loading = true

            axios.get(url_api + 'quices/get_elements/' + this.quiz.id)
            .then(response => {
                this.elements = response.data.elementos
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
        delete_element: function(){
            this.loading = true
            axios.get(url_api + 'quices/delete_element/' + this.quiz.id + '/' + this.element_id)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    this.get_elements()
                } else {
                    toastr['error']('Ocurrió un error al eliminar')
                }
            })
            .catch(function(error) { console.log(error) })
        },
        clear_form: function() {
            this.key_element = -1
            this.form_values.texto = ''
            this.form_values.detalle = ''
            this.form_values.orden = this.elements.length
            this.element_id = 0
            $('#field-texto').focus()
        },
        send_file_form: function(){
            this.loading_file = true
            let form_data = new FormData();
            form_data.append('archivo', this.file);
            form_data.append('quiz_id', this.quiz.id);

            axios.post(url_api + 'quices/upload_image/' + this.quiz.id, form_data, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
                //Mostrar imagen
                if ( response.data.status == 1 ) { this.imagen = response.data.imagen }

                //Mostrar respuesta html, si existe
                if ( response.data.html ) { $('#upload_response').html(response.data.html) }

                //Limpiar formulario
                $('#field-file').val('')

                this.loading_file = false
            })
            .catch(function (error) { console.log(error) })
        },
        handle_file_upload(){
            this.file = this.$refs.file_field.files[0];
        },
        delete_image: function(){
            this.loading_file = true
            axios.get(url_api + 'quices/delete_element/' + this.quiz.id + '/' + this.imagen.id)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    toastr['info']('Imagen eliminada')
                    this.imagen = {id: 0}
                }
                this.loading_file = false
            })
            .catch(function(error) { console.log(error) })
        },
    }
})
</script>