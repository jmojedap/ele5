<?php $this->load->view('assets/summernote') ?>

<script>
    /*$(document).ready(function(){
        $('#field-contenido').summernote({
            lang: 'es-ES',
            height: 1200
        });
    });*/
</script>

<div id="editPostApp" class="center_box_750">
    <form accept-charset="utf-8" method="POST" id="post_form" @submit.prevent="send_form">
        <div class="mb-2">
            <button class="btn btn-success w120p" type="submit">
                Guardar
            </button>
        </div>
        <hr>
        <div class="mb-2 row">
            <label for="nombre_post" class="col-md-4 col-form-label text-right">Título</label>
            <div class="col-md-8">
                <input
                    name="nombre_post" placeholder="" title=""
                    type="text" required class="form-control"
                    v-model="fields.nombre_post"
                    >
            </div>
        </div>

        <div class="mb-3 row">
            <label for="referente_1_id" class="col-md-4 col-form-label text-right">ID Tema</label>
            <div class="col-md-8">
                <input
                    name="referente_1_id" type="text" class="form-control"
                    required
                    title="ID Tema" placeholder="ID Tema"
                    v-model="fields.referente_1_id"
                >
            </div>
        </div>

        <div class="mb-2">
            <label for="contenido" class="form-control-label">Contenido</label>
            <textarea name="contenido" id="field-contenido" class="form-control" v-model="fields.contenido" rows="20"></textarea>
        </div>
    </form>
</div>

<script>
    new Vue({
        el: '#editPostApp',
        created: function(){
            //this.get_list();
        },
        data: {
            post_id: <?= $row->id ?>,
            fields: <?= json_encode($row) ?>,
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'posts/update/' + this.post_id, $('#post_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['success']('Guardado');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>