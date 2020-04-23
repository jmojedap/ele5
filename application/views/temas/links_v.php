<div id="links_app">
    <div class="row">
        <div class="col-md-7">
            <table class="table bg-white">
                <tbody>
                    <!-- LISTADO DE LINKS -->
                    <tr v-for="(link, key) in links" v-bind:class="{'table-success': key == link_key}">
                        <td>
                            <dl class="row">
                                <dt class="col-md-3 text-right"></dt>
                                <dd class="col-md-9"><h3>{{ link.titulo }}</h3></dd>

                                <dt class="col-md-3 text-right">Link</dt>
                                <dd class="col-md-9">
                                    <a v-bind:href="link.url" target="_blank">
                                        {{ link.url }}
                                    </a>
                                </dd>
                                <dt class="col-md-3 text-right">Descripción</dt>
                                <dd class="col-md-9">{{ link.descripcion }}</dd>
                                
                                <dt class="col-md-3 text-right">Palabras clave</dt>
                                <dd class="col-md-9">{{ link.palabras_clave }}</dd>

                                <dt class="col-md-3 text-right">Componente</dt>
                                <dd class="col-md-9">{{ link.componente_id | componente_name}}</dd>
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
                            <label for="descripcion" class="col-md-4 col-form-label text-right">Descripción</label>
                            <div class="col-md-8">
                                <textarea
                                    type="text"
                                    id="field-descripcion"
                                    name="descripcion"
                                    required
                                    class="form-control"
                                    placeholder="Descripción"
                                    title="Descripción"
                                    v-bind:value="form_values.descripcion"
                                    rows="3"
                                    ></textarea>
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
                                <?php echo form_dropdown('componente_id', $options_componente, '0', 'class="form-control" v-bind:value="form_values.componente_id"') ?>
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
// Variables
//-----------------------------------------------------------------------------
    var arr_componentes = <?php echo json_encode($arr_componentes); ?>

// Filters
//-----------------------------------------------------------------------------
    Vue.filter('componente_name', function (value) {
        if (!value) return '';
        value = arr_componentes[value];
        return value;
    });

// Vue App
//-----------------------------------------------------------------------------
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
            titulo: '',
            url: '',
            descripcion: '',
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
            this.clean_form();
            $('#field-titulo').focus();
        },
        set_current: function(key) {
            this.link_key = key;
            this.link_id = this.links[key].id;
            this.form_values.titulo = this.links[key].titulo;
            this.form_values.url = this.links[key].url;
            this.form_values.descripcion = this.links[key].descripcion;
            this.form_values.palabras_clave = this.links[key].palabras_clave;
            this.form_values.componente_id = '0' + this.links[key].componente_id;
        },
        save_link: function() {
            axios.post(this.app_url + 'temas/save_link/' + this.tema_id + '/' + this.link_id, $('#link_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['success']('Guardado');
                    }
                    this.get_list();
                    this.clean_form();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        clean_form: function(){
            this.form_values.titulo = '';
            this.form_values.url = '';
            this.form_values.descripcion = '';
            this.form_values.palabras_clave = '';
            this.form_values.componente_id = '';
        },
        delete_element: function() {
            axios.get(this.app_url + 'temas/delete_link/' + this.tema_id + '/' + this.link_id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['info']('Link eliminado');
                        this.get_list();
                        this.new_link();
                    } else {
                        toastr['error']('Ocurrió un error al eliminar');
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
});
</script>