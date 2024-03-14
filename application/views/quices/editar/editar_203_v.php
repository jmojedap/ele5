<div id="editar_quiz_app">
    <div class="card center_box_920">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="quiz_form" @submit.prevent="send_form">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="id" value="<?= $row->id ?>">

                    <div class="mb-2 row">
                        <label for="nombre_quiz" class="col-md-4 col-form-label text-right">Nombre <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input
                                name="nombre_quiz" type="text" class="form-control"
                                required
                                title="Nombre" placeholder="Nombre"
                                v-model="fields.nombre_quiz"
                            >
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="cod_quiz" class="col-md-4 col-form-label text-right">Código evidencia</label>
                        <div class="col-md-8">
                            <input
                                name="cod_quiz" type="text" class="form-control"
                                title="Código evidencia" placeholder="Código evidencia"
                                v-model="fields.cod_quiz"
                            >
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="texto_enunciado" class="col-md-4 col-form-label text-right">Texto o enunciado <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <textarea
                                name="texto_enunciado" rows="2" class="form-control" required
                                title="Enunciado especial" placeholder="Enunciado especial"
                                v-model="fields.texto_enunciado"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="opciones" class="col-md-4 col-form-label text-end text-right">Opciones de respuesta <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <textarea
                                name="opciones" rows="2" class="form-control"
                                required
                                title="Opciones de respuesta"
                                v-model="fields.opciones"
                            ></textarea>
                            <small class="form-text text-muted">Opciones de respuesta separadas por coma</small>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="clave" class="col-md-4 col-form-label text-end text-right">Respuesta correcta <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="clave" v-model="fields.clave" class="form-select form-control" required v-bind:disabled="arrOpcionesRespuesta.length == 0">
                                <option v-for="optionRespuesta in arrOpcionesRespuesta" v-bind:value="optionRespuesta">{{ optionRespuesta }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-2 row">
                        <label for="tipo_quiz_id" class="col-md-4 col-form-label text-right">Tipo <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="tipo_quiz_id" v-model="fields.tipo_quiz_id" class="form-control" required>
                                <option v-for="(option_tipo_quiz_id, key_tipo_quiz_id) in options_tipo_quiz_id" v-bind:value="key_tipo_quiz_id">{{ option_tipo_quiz_id }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="area_id" class="col-md-4 col-form-label text-end text-right">Área <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="area_id" v-model="fields.area_id" class="form-select form-control" required>
                                <option value="">[ Todos ]</option>
                                <option v-for="optionArea in arrAreas" v-bind:value="optionArea.id">{{ optionArea.name }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-2 row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        </div>
                    </div>
                <fieldset>
            </form>    
            
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
    var row = <?= json_encode($row) ?>;
    row.tipo_quiz_id = '0<?= $row->tipo_quiz_id ?>';
    row.formato = '0<?= $row->formato ?>';

// VueApp
//-----------------------------------------------------------------------------
var editar_quiz_app = new Vue({
    el: '#editar_quiz_app',
    data: {
        fields: row,
        options_tipo_quiz_id: <?= json_encode($options_tipo_quiz_id) ?>,
        options_formato: <?= json_encode($options_formato) ?>,
        arrAreas: <?= json_encode($arrAreas) ?>,
        loading: false,
    },
    methods: {
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('quiz_form'))
            axios.post(url_api + 'quices/save/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    },
    computed: {
        arrOpcionesRespuesta: function(){
            var opcionesRespuesta = []
            if ( this.fields.opciones.length > 0 ) {
                opcionesRespuesta = this.fields.opciones.split(',')
            }
            return opcionesRespuesta
        },
    },
})
</script>