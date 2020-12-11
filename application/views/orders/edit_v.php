<div id="edit_app">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="send_form">
                    <div class="form-group row">
                        <label for="bill" class="col-md-4 col-form-label text-right">Factura</label>
                        <div class="col-md-8">
                            <input
                                name="bill" type="text" class="form-control"
                                title="No. Factura"
                                v-model="row.bill"
                            >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="shipping_code" class="col-md-4 col-form-label text-right">Guía transportadora</label>
                        <div class="col-md-8">
                            <input
                                name="shipping_code" type="text" class="form-control"
                                v-model="row.shipping_code"
                            >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user_id" class="col-md-4 col-form-label text-right">ID Estudiante</label>
                        <div class="col-md-8">
                            <input
                                name="user_id" type="text" class="form-control"
                                title="ID Estudiante" placeholder="ID Estudiante"
                                v-model="row.user_id"
                            >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="notes_admin" class="col-md-4 col-form-label text-right">Notas internas</label>
                        <div class="col-md-8">
                            <textarea
                                name="notes_admin" type="text" class="form-control"
                                rows="3"
                                v-model="row.notes_admin"
                            ></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-success w120p" type="submit">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#edit_app',
        created: function(){
            //this.get_list();
        },
        data: {
            row: <?= json_encode($row) ?>
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'orders/admin_update/' + this.row.id, $('#edit_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['success']('Datos guardados')
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>