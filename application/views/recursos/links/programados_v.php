<?php $this->load->view('assets/momentjs') ?>
<script src="<?php echo URL_RESOURCES . 'js/pcrn_en.js' ?>"></script>

<?php
    $arr_grupos = $this->session->userdata('arr_grupos');
    $grupos = array();

    $grupo_id = ( count($arr_grupos) > 0 ) ? $arr_grupos[0] : 0 ;

    foreach ($arr_grupos as $grupo_id)
    {
        $grupos[$grupo_id] = $this->App_model->nombre_grupo($grupo_id);
    }
    
?>

<div id="app_links_programados">
<div class="row">
    <div class="col-md-4">
        <h3>Mis grupos</h3>
        <div class="list-group">
            <button
                v-for="(grupo, grupo_key) in grupos"
                type="button"
                class="list-group-item list-group-item-action"
                v-bind:class="{'active': grupo_key == grupo_id }"
                v-on:click="set_grupo(grupo_key)"
            >
                {{ grupo }}
            </button>
        </div>
    </div>
    <div class="col-md-8">
        <h3>Links programados</h3>
        <table class="table bg-white">
            <thead>
                <th>Link</th>
                <th>Fecha</th>
                <th>Nivel Área</th>
                <th width="50px"></th>
            </thead>
            <tbody>
                <tr v-for="(evento, key) in list" v-show="evento.grupo_id == grupo_id">
                    <td>
                        <a v-bind:href="evento.url" target="_blank" v-bind:title="evento.url">
                            {{ evento.titulo }}
                        </a>
                    </td>
                    <td>{{ evento.fecha_inicio }} <span class="text-muted">({{ evento.fecha_inicio | ago }})</span></td>
                    <td>
                        <span class="etiqueta nivel w1">{{ evento.nivel }}</span>
                        <span class="etiqueta_a w3" v-bind:class="`etiqueta_a` + evento.area_id">
                            {{ evento.area_id | area_name }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" v-on:click="delete_link_programado(key)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
    var arr_areas = <?php echo json_encode($arr_areas); ?>;
    var arr_tipos = <?php echo json_encode($arr_tipos); ?>;
    var arr_componentes = <?php echo json_encode($arr_componentes); ?>;

// Filtros
//-----------------------------------------------------------------------------

    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

    Vue.filter('area_name', function (value) {
        if (!value) return '';
        value = arr_areas[value];
        return value;
    });

    Vue.filter('tipo_name', function (value) {
        if (!value) return '';
        value = arr_tipos[value];
        return value;
    });

    Vue.filter('componente_name', function (value) {
        if (!value) return '';
        value = arr_componentes[value];
        return value;
    });

// Vue App
//-----------------------------------------------------------------------------
    new Vue({
        el: '#app_links_programados',
        created: function(){
            this.get_list();
        },
        data: {
            grupos: <?php echo json_encode($grupos); ?>,
            grupo_id: <?php echo $grupo_id ?>,
            list: [],
        },
        methods: {
            get_list: function(){
                axios.get(app_url + 'recursos/get_links_programados/')
                .then(response => {
                    this.list = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            set_grupo: function(grupo_key){
                this.grupo_id =grupo_key;
            },
            delete_link_programado: function(key){
                var evento_id = this.list[key].id;
                axios.get(app_url + 'eventos/delete/' + evento_id)
                .then(response => {
                    if ( response.data.qty_deleted > 0 )
                    {
                        this.list.splice(key,1);   
                        toastr['info']('Programación de link eliminada');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>