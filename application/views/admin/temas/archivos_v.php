<div id="archivos_app">
    <form accept-charset="utf-8" method="POST" id="archivo_form" @submit.prevent="save_archivo">
        <input type="hidden" name="id" v-bind:value="form_values.id">
        <table class="table bg-white">
            <thead>
                <th>Archivo</th>
                <th>Tipo</th>
                <th>Disponible</th>
                <th width="50px">
                    <button class="btn btn-success btn-block" type="button" title="Nuevo archivo" v-on:click="new_archivo">
                        Nuevo
                    </button>
                </th>
            </thead>
            <tbody>
                <!-- FORMULARIO PARA ARCHIVOS -->
                <tr>
                    <td>
                        <input
                            id="field-nombre_archivo"
                            type="text"
                            name="nombre_archivo"
                            class="form-control"
                            placeholder="Nombre del archivo"
                            title="Nombre del archivo"
                            required
                            v-model="form_values.nombre_archivo">
                    </td>
                    <td>
                        <?php echo form_dropdown('tipo_archivo_id', $options_type, '', 'class="form-control" v-bind:value="`0` + form_values.tipo_archivo_id" required') ?>
                    </td>

                    <td>
                        <?php echo form_dropdown('disponible', $options_yn, '', 'class="form-control" v-bind:value="`0` + form_values.disponible" required') ?>
                    </td>
                    
                    <td>
                        <button class="btn btn-primary" type="submit">
                            Guardar
                        </button>
                    </td>
                </tr>

                <!-- LISTADO DE LINKS -->
                <tr v-for="(archivo, key) in archivos" v-bind:class="{'table-success': key == archivo_key}">
                    <td>
                        <a v-bind:href="`<?= URL_UPLOADS ?>` + typeName(archivo.tipo_archivo_id, 'slug') + `/` + archivo.nombre_archivo" target="_blank">
                            {{ archivo.nombre_archivo }}
                        </a>
                    </td>
                    <td>
                        {{ typeName(archivo.tipo_archivo_id) }}
                    </td>
                    <td>
                        {{ archivo.disponible | yn_name }}
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
var arr_types = <?php echo json_encode($arr_types); ?>;
var arr_yn = <?php echo json_encode($arr_yn); ?>;

Vue.filter('type_name', function (value) {
    if (!value) return '';
    value = arr_types[value];
    return value;
});

Vue.filter('yn_name', function (value) {
    if (!value) return '';
    value = arr_yn[value];
    return value;
});

new Vue({
    el: '#archivos_app',
    created: function() {
        this.get_list();
    },
    data: {
        app_url: '<?php echo base_url() ?>',
        tema_id: <?php echo $row->id ?>,
        archivos: [],
        archivo: {},
        form_values: {
            id: 0,
            nombre_archivo: '',
            tipo_archivo_id: '',
            disponible: ''
        },
        archivo_key: -1,
        archivo_id: 0,
        arrTypes: <?= json_encode($arr_types) ?>,
    },
    methods: {
        get_list: function() {
            axios.get(this.app_url + 'admin/temas/get_archivos/' + this.tema_id)
                .then(response => {
                    this.archivos = response.data.archivos;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        new_archivo: function() {
            this.archivo_key = -1;
            this.archivo_id = 0;
            this.form_values = {
                id: 0,
                nombre_archivo: '',
                tipo_archivo_id: '',
                disponible: '0'
            }
            $('#field-nombre_archivo').focus();
        },
        set_current: function(key) {
            this.archivo_key = key;
            this.archivo_id = this.archivos[key].id;

            this.form_values = this.archivos[key];
            //this.form_values.tipo_archivo_id = '0' + this.archivos[key].tipo_archivo_id;
        },
        save_archivo: function() {
            axios.post(this.app_url + 'admin/temas/save_archivo/' + this.tema_id + '/' + this.archivo_id, $('#archivo_form').serialize())
                .then(response => {
                    toastr["success"](response.data.message);
                    this.get_list();
                    this.new_archivo();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        delete_element: function() {
            axios.get(this.app_url + 'admin/temas/delete_archivo/' + this.tema_id + '/' + this.archivo_id)
                .then(response => {
                    toastr['info'](response.data.message);
                    this.get_list();
                    this.new_archivo();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        typeName: function(value = '', field = 'name'){
            var typeName = ''
            var item = this.arrTypes.find(row => row.id == value)
            if ( item != undefined ) typeName = item[field]
            return typeName
        },
    }
});
</script>