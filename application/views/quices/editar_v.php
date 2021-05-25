<?php
    $options_formato = array();

    //Formato para tipo L2
    if ( $row->tipo_quiz_id == 112 )
    {
        $options_formato['01'] = 'Elementos separados';
        $options_formato['02'] = 'Elementos continuos';
    }
?>

<div id="editar_quiz_app">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="quiz_form" @submit.prevent="send_form">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="id" value="<?= $row->id ?>">

                    <div class="form-group row">
                        <label for="nombre_quiz" class="col-md-4 col-form-label text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                name="nombre_quiz" type="text" class="form-control"
                                required
                                title="Nombre" placeholder="Nombre"
                                v-model="form_values.nombre_quiz"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tipo_quiz_id" class="col-md-4 col-form-label text-right">Tipo</label>
                        <div class="col-md-8">
                            <select name="tipo_quiz_id" v-model="form_values.tipo_quiz_id" class="form-control" required>
                                <option v-for="(option_tipo_quiz_id, key_tipo_quiz_id) in options_tipo_quiz_id" v-bind:value="key_tipo_quiz_id">{{ option_tipo_quiz_id }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Mostrar si options_formato tiene elementos -->
                    <div class="form-group row" v-show="Object.keys(options_formato).length">
                        <label for="formato" class="col-md-4 col-form-label text-right">Formato</label>
                        <div class="col-md-8">
                            <select name="formato" v-model="form_values.formato" class="form-control" required>
                                <option v-for="(option_formato, key_formato) in options_formato" v-bind:value="key_formato">{{ option_formato }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="clave" class="col-md-4 col-form-label text-right">Clave</label>
                        <div class="col-md-8">
                            <input
                                name="clave" type="text" class="form-control"
                                required
                                title="Clave" placeholder="Clave"
                                v-model="form_values.clave"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="cod_quiz" class="col-md-4 col-form-label text-right">Código evidencia</label>
                        <div class="col-md-8">
                            <input
                                name="cod_quiz" type="text" class="form-control"
                                title="Código evidencia" placeholder="Código evidencia"
                                v-model="form_values.cod_quiz"
                            >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="texto_enunciado" class="col-md-4 col-form-label text-right">Enunciado especial</label>
                        <div class="col-md-8">
                            <textarea
                                name="texto_enunciado" rows="3" class="form-control"
                                title="Enunciado especial" placeholder="Enunciado especial"
                                v-model="form_values.texto_enunciado"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
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
        form_values: row,
        options_tipo_quiz_id: <?= json_encode($options_tipo_quiz_id) ?>,
        options_formato: <?= json_encode($options_formato) ?>,
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
    }
})
</script>