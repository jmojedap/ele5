<div id="editPost">
    <div class="center_box_750">
        <form accept-charset="utf-8" method="POST" id="postForm" @submit.prevent="handleSubmit">
            <fieldset v-bind:disabled="loading">
                <input type="hidden" name="id" value="<?= $row->id ?>">
                <div class="mb-3 row">
                    <label for="nombre_post" class="col-md-4 col-form-label text-end text-right">Título</label>
                    <div class="col-md-8">
                        <input
                            name="nombre_post" type="text" class="form-control"
                            required
                            title="Título" placeholder="Título"
                            v-model="fields.nombre_post"
                        >
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="resumen" class="col-md-4 col-form-label text-end text-right">Descripción</label>
                    <div class="col-md-8">
                        <textarea
                            name="resumen" class="form-control" rows="2" required
                            title="Descripción corta de la unidad" placeholder="Descripción"
                            v-model="fields.resumen"
                        ></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="integer_1" class="col-md-4 col-form-label text-end text-right">Número unidad</label>
                    <div class="col-md-8">
                        <input
                            name="integer_1" class="form-control" rows="rows" required type="number" min="1" max="10"
                            title="Número unidad" placeholder="Número unidad"
                            v-model="fields.integer_1"
                        >
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="texto_1" class="col-md-4 col-form-label text-end text-right">IDs Contenidos</label>
                    <div class="col-md-8">
                        <textarea
                            name="texto_1" rows="2" class="form-control"
                            title="IDs Lecturas" placeholder="IDs Contenidos HTML"
                            v-model="fields.texto_1"
                        ></textarea>
                        <small class="form-text text-muted">Escriba los ID's de los contenidos HTML donde debe aparecer esta unidad, separados por coma.
                            Ejemplo: 125,765,385,74685
                        </small>
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

<script>
// Variables
//-----------------------------------------------------------------------------
const form = document.getElementById('postForm');

// VueApp
//-----------------------------------------------------------------------------
var editPost = new Vue({
    el: '#editPost',
    data: {
        fields: <?= json_encode($row) ?>,
        loading: false,
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formData = new FormData(document.getElementById('postForm'))
            axios.post(URL_API + 'posts/save/', formData)
            .then(response => {
                if (response.data.saved_id > 0) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            }).catch(function(error) { console.log(error)})
        },
    }
})
</script>