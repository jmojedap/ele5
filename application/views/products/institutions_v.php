<div id="product_institutions" class="center_box_750">
    <?php if ( $this->session->userdata('role') <= 2 ) { ?>
        <div class="card mb-2">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="institutions_form" @submit.prevent="add_institution" clas="form-horizontal">
                    <div class="form-group row">
                        <label for="post_id" class="col-md-3 col-form-label text-right">ID Institución</label>
                        <div class="col-md-7">
                            <input
                                name="institution_id" id="field-institution_id" type="text" class="form-control"
                                required
                                v-model="institution_id"
                            >
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">
                                Agregar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>

    <table class="table bg-white">
        <thead>
            <th class="table-warning" width="50px">ID</th>
            <th width="50px">Código</th>
            <th>Nombre</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(institution, institution_key) in institutions">
                <td class="table-warning">{{ institution.id }}</td>
                <td>{{ institution.cod }}</td>
                <td>
                    <a v-bind:href="`<?php echo base_url("instituciones/index/") ?>` + `/` + institution.id">
                        {{ institution.title }}
                    </a>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning" v-on:click="delete_meta(institution.meta_id)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    new Vue({
        el: '#product_institutions',
        created: function(){
            //this.get_list();
        },
        data: {
            product_id: <?php echo $row->id ?>,
            institutions: <?php echo json_encode($institutions->result()) ?>,
            institution_id: ''
        },
        methods: {
            add_institution: function(){
                axios.get(app_url + 'products/add_institution/' + this.product_id + '/' + this.institution_id)
                .then(response => {
                    console.log(response.data)
                    window.location = app_url + 'products/institutions/' + this.product_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            delete_meta: function(meta_id){
                axios.get(app_url + 'products/delete_meta/' + this.product_id + '/' + meta_id)
                .then(response => {
                    console.log(response.data)
                    window.location = app_url + 'products/institutions/' + this.product_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>