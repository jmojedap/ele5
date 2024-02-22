<?php
    $arrFileCollections = $this->Item_model->arr_options('categoria_id =  36');
?>

<div id="add_file_app">
    <div class="card center_box_750">
        <div class="card-body">
            <div class="mb-3 row">
                <div class="col-md-8">
                    <select name="album_id" v-model="fields.album_id" class="form-select form-control">
                        <option value="">(Ninguna)</option>
                        <option v-for="optionFileCollection in arrFileCollections" v-bind:value="optionFileCollection.cod">{{ optionFileCollection.name }}</option>
                    </select>
                </div>
                <label for="album_id" class="col-md-4 col-form-label text-start">Colección</label>
            </div>
            <?php $this->load->view('common/bs4/upload_file_form_v') ?>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#add_file_app',
        created: function(){
            //this.get_list();
        },
        data: {
            loading: false,
            fields: {
                album_id: ''
            },
            arrFileCollections: <?= json_encode($arrFileCollections) ?>,
            file: '',
        },
        methods: {
            submitFileForm: function(){
                this.loading = true
                let form_data = new FormData();
                form_data.append('file_field', this.file);
                form_data.append('table_id', '1')
                form_data.append('album_id', this.fields.album_id)

                axios.post(URL_API + 'files/upload/', form_data, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => {
                    console.log(response.data);
                    //Ir a la vista de la imagen
                    if ( response.data.status == 1 ) {
                        window.location = URL_APP + 'files/info/' + response.data.row.id;
                    }
                    //Mostrar respuesta html, si existe
                    if ( response.data.html ) { $('#upload_response').html(response.data.html); }
                    //Limpiar formulario
                    $('#field-file').val(''); 
                    this.loading = false
                })
                .catch(function (error) { console.log(error) })
            },
            handleFileUpload(){
                this.file = this.$refs.file_field.files[0];
            },
        }
    });
</script>