<div id="links_app">
    <div class="row">
        <div class="col-md-7">
            <table class="table bg-white">
                <tbody>
                    <!-- LISTADO DE LINKS -->
                    <tr v-for="(link, key) in links" v-bind:class="{'table-success': key == link_key}">
                        <td>
                            <dl class="row">
                                <dt class="col-md-3">Título</dt>
                                <dd class="col-md-9">{{ link.titulo }}</dd>

                                <dt class="col-md-3">Link</dt>
                                <dd class="col-md-9">
                                    <a v-bind:href="`<?php echo base_url("temas/explore/?list=") ?>` + link.id" class="clase">
                                        {{ link.url }}
                                    </a>
                                </dd>
                                <dt class="col-md-3">Palabras clave</dt>
                                <dd class="col-md-9">{{ link.palabras_clave }}</dd>
                            </dl>
                        </td>
                        
                        <td width="80px">
                            <button class="btn btn-light btn-sm" type="button" v-on:click="set_current(key)">
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
        </div>
        <div class="col-md-5">
            <button class="btn btn-success w3 mb-2" type="button" title="Nuevo link" v-on:click="new_link">
                Nuevo
            </button>

            <div class="card">
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="link_form" @submit.prevent="save_link">
                        <input type="hidden" name="id" v-bind:value="form_values.id">
                        <div class="form-group row">
                            <label for="titulo" class="col-md-4 col-form-label text-right">Título</label>
                            <div class="col-md-8">
                                <input
                                    id="field-titulo"
                                    type="text"
                                    name="titulo"
                                    class="form-control"
                                    placeholder="Título del link"
                                    title="Título del link"
                                    autofocus
                                    v-bind:value="form_values.titulo">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="url" class="col-md-4 col-form-label text-right">URL</label>
                            <div class="col-md-8">
                                <input
                                    id="field-url"
                                    type="url"
                                    name="url"
                                    class="form-control"
                                    placeholder="URL del link"
                                    title="URL del link"
                                    required
                                    v-bind:value="form_values.url">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="palabras_clave" class="col-md-4 col-form-label text-right">Palabras clave</label>
                            <div class="col-md-8">
                                <input
                                    type="text"
                                    id="field-palabras_clave"
                                    name="palabras_clave"
                                    required
                                    class="form-control"
                                    placeholder="Palabras clave"
                                    title="Palabras clave"
                                    v-bind:value="form_values.palabras_clave"
                                    >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="componente_id" class="col-md-4 col-form-label text-right">Componente</label>
                            <div class="col-md-8">
                                <?php echo form_dropdown('componente_id', $options_componente, '0', 'class="form-control" v-model="form_values.componente_id"') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-primary w3" type="submit">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('comunes/bs4/modal_simple_delete_v') ?>
</div>

<script>
new Vue({
    el: '#links_app',
    created: function() {
        this.get_list();
    },
    data: {
        app_url: '<?php echo base_url() ?>',
        tema_id: <?php echo $row->id ?>,
        links: [],
        link: {},
        form_values: {
            id: 0,
            name: '',
            palabras_clave: '',
            componente_id: ''
        },
        form_values_new: {
            id: 0,
            titulo: '',
            url: '',
            palabras_clave: '',
            componente_id: ''
        },
        link_key: -1,
        link_id: 0
    },
    methods: {
        get_list: function() {
            axios.get(this.app_url + 'temas/get_links/' + this.tema_id)
                .then(response => {
                    this.links = response.data.links;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        new_link: function() {
            this.link_key = -1;
            this.link_id = 0;
            this.form_values = this.form_values_new;
            $('#field-titulo').focus();
        },
        set_current: function(key) {
            this.link_key = key;
            this.link_id = this.links[key].id;
            this.form_values = this.links[key];
            this.form_values.componente_id = '0' + this.links[key].componente_id;
        },
        save_link: function() {
            axios.post(this.app_url + 'temas/save_link/' + this.tema_id + '/' + this.link_id, $('#link_form').serialize())
                .then(response => {
                    toastr["success"](response.data.message);
                    this.get_list();
                    this.form_values = this.form_values_new;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        delete_element: function() {
            axios.get(this.app_url + 'temas/delete_link/' + this.tema_id + '/' + this.link_id)
                .then(response => {
                    toastr['info'](response.data.message);
                    this.get_list();
                    this.new_link();
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
});
</script>