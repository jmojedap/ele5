<div id="links_app">
    <form accept-charset="utf-8" method="POST" id="link_form" @submit.prevent="save_link">
        <input type="hidden" name="id" v-bind:value="form_values.id">
        <table class="table bg-white">
            <thead>
                <th>Título</th>
                <th>URL</th>
                <th width="50px">
                    <button class="btn btn-success btn-block" type="button" title="Nuevo link" v-on:click="new_link">
                        Nuevo
                    </button>
                </th>
            </thead>
            <tbody>
                <!-- FORMULARIO PARA LINKS -->
                <tr>
                    <td>
                        <input
                            id="field-titulo"
                            type="text"
                            name="titulo"
                            class="form-control"
                            placeholder="Título del link"
                            title="Título del link"
                            autofocus
                            v-bind:value="form_values.titulo">
                    </td>
                    <td>
                        <input
                            id="field-url"
                            type="url"
                            name="url"
                            class="form-control"
                            placeholder="URL del link"
                            title="URL del link"
                            required
                            v-bind:value="form_values.url">
                    </td>
                    
                    <td>
                        <button class="btn btn-primary" type="submit">
                            Guardar
                        </button>
                    </td>
                </tr>

                <!-- LISTADO DE LINKS -->
                <tr v-for="(link, key) in links" v-bind:class="{'table-success': key == link_key}">
                    <td>
                        {{ link.titulo }}
                    </td>
                    <td>
                        <a v-bind:href="`<?php echo base_url("temas/explore/?list=") ?>` + link.id" class="clase">
                            {{ link.url }}
                        </a>
                    </td>
                    <td>
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
    </form>
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
            url: ''
        },
        form_values_new: {
            id: 0,
            titulo: '',
            url: ''
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