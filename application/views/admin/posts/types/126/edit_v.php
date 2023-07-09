<?php
    $arrLocalidad = $this->Item_model->arr_options('category_id = 121');
    $arrManzana = $this->App_model->arr_options_post("type_id = 311");

    //Opciones modalidades de escuela    
    $arrModalidad = file_get_contents(PATH_CONTENT . "json/options/cuidado_modalidades.json");
?>

<div id="editPost">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="postForm" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                    <input type="hidden" name="related_1" v-model="fields.related_1">

                    <div class="mb-3 row">
                        <label for="post_name" class="col-md-4 col-form-label text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                name="post_name" type="text" class="form-control"
                                required
                                title="Nombre" placeholder="Nombre"
                                v-model="fields.post_name"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="related_2" class="col-md-4 col-form-label text-right">Manzana de cuidado</label>
                        <div class="col-md-8">
                            <select name="related_2" v-model="fields.related_2" class="form-control" required v-on:change="setLocalidad">
                                <option v-for="optionManzana in arrManzana" v-bind:value="optionManzana.str_cod">{{ optionManzana.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="text_1" class="col-md-4 col-form-label text-right">Modalidad sesión</label>
                        <div class="col-md-8">
                            <select name="text_1" v-model="fields.text_1" class="form-control" required>
                                <option v-for="optionModalidad in arrModalidad" v-bind:value="optionModalidad.name">{{ optionModalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="integer_1" class="col-md-4 col-form-label text-right">Módulo | Núm. sesión</label>
                        <div class="col-md-4">
                            <input
                                name="integer_1" type="number" class="form-control" min="1"
                                title="Módulo | Núm. sesión"
                                v-model="fields.integer_1"
                            >
                        </div>
                        <div class="col-md-4">
                            <input
                                name="integer_2" type="number" class="form-control" min="1"
                                v-model="fields.integer_2"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date_1" class="col-md-4 col-form-label text-right">Fecha y hora</label>
                        <div class="col-md-8">
                            <input
                                name="date_1" type="datetime-local" class="form-control"
                                required
                                v-model="fields.date_1"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="excerpt" class="col-md-4 col-form-label text-right">Observaciones</label>
                        <div class="col-md-8">
                            <textarea
                                name="excerpt" class="form-control"
                                v-model="fields.excerpt"
                            ></textarea>
                        </div>
                    </div>

                    
                    <div class="mb-3 row">
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
row.related_1 = '0<?= $row->related_1 ?>';
row.related_2 = '0<?= $row->related_2 ?>';

// VueApp
//-----------------------------------------------------------------------------
var editPost = new Vue({
    el: '#editPost',
    data: {
        fields: row,
        loading: false,
        arrLocalidad: <?= json_encode($arrLocalidad) ?>,
        arrManzana: <?= json_encode($arrManzana) ?>,
        arrModalidad: <?= $arrModalidad ?>,
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('postForm'))
            axios.post(URL_API + 'posts/save/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setLocalidad: function(){
            console.log('Consultando localidad')
            var item = this.arrManzana.find(row => row.str_cod == this.fields.related_2)
            if ( item != undefined ) this.fields.related_1 = '0' + item['related_1']
        },
    }
})
</script>