<div id="pa_app" class="container">
        
    <table class="table bg-white">
        <thead>
            <th>Texto pregunta</th>
            <th>Tipo</th>
            <th>Creador: Usuario ID</th>
            <th width="50px">
                <button class="btn btn-success btn-block" type="button" title="Agregar pregunta" data-toggle="modal" data-target="#modal_form" v-on:click="new_pa">
                    Agregar
                </button>
            </th>
        </thead>
        <tbody>
            <!-- LISTADO DE PREGUNTAS -->
            <tr v-for="(pregunta, key) in preguntas_abiertas" v-bind:class="{'table-success': key == pa_key}">
                <td>
                    {{ pregunta.contenido }}
                </td>
                <td>
                    <span class="text-info" v-show="pregunta.publica == 1">Editores</span>
                    <span class="text-success" v-show="pregunta.publica == 2">Institucional</span>
                </td>
                <td>
                    {{ pregunta.usuario_id }}
                </td>
                <td>
                    <button class="btn btn-light btn-sm" type="button" data-toggle="modal" data-target="#modal_form" v-on:click="set_current(key)">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" type="button" data-toggle="modal"
                        data-target="#delete_modal" v-on:click="set_current(key)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            
        </tbody>
    </table>

    <!-- Ventana Modal Formulario -->
    <div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="modal_form" aria-hidden="true">
        <form accept-charset="utf-8" method="POST" id="pa_form" @submit.prevent="save_pa">
            <input type="hidden" name="id" v-bind:value="form_values.id">
            <input type="hidden" name="referente_2_id" value="1">

            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pregunta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="contenido" class="col-md-4 col-form-label">Texto pregunta</label>
                        <div class="col-md-8">
                            <textarea
                                name="contenido"
                                rows="3"
                                required
                                class="form-control"
                                placeholder="Texto de la pregunta"
                                title="Texto de la pregunta"
                                v-model="form_values.contenido"
                                ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </div>
            </div>
        </form>
    </div>

    <?php $this->load->view('comunes/bs4/modal_simple_delete_v') ?>
</div>

<script>
new Vue({
    el: '#pa_app',
    created: function() {
        this.get_list();
    },
    data: {
        app_url: '<?php echo base_url() ?>',
        tema_id: <?php echo $row->id ?>,
        preguntas_abiertas: [],
        pa: {},
        form_values: {
            id: 0,
            contenido: ''
        },
        form_values_new: {
            id: 0,
            contenido: ''
        },
        pa_key: -1,
        pa_id: 0
    },
    methods: {
        get_list: function() {
            axios.get(this.app_url + 'temas/get_pa/' + this.tema_id)
                .then(response => {
                    this.preguntas_abiertas = response.data.preguntas_abiertas;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        new_pa: function() {
            this.pa_key = -1;
            this.pa_id = 0;
            this.form_values = this.form_values_new;
            $('#field-contenido').focus();
        },
        set_current: function(key) {
            this.pa_key = key;
            this.pa_id = this.preguntas_abiertas[key].id;
            this.form_values = this.preguntas_abiertas[key];
        },
        save_pa: function() {
            axios.post(this.app_url + 'temas/save_pa/' + this.tema_id + '/' + this.pa_id, $('#pa_form').serialize())
                .then(response => {
                    toastr["success"](response.data.message);
                    this.get_list();
                    this.form_values = this.form_values_new;
                    $('#modal_form').modal('hide')
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        delete_element: function() {
            axios.get(this.app_url + 'temas/delete_pa/' + this.tema_id + '/' + this.pa_id)
                .then(response => {
                    toastr['info'](response.data.message);
                    this.get_list();
                    this.new_pa();
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
});
</script>