<div id="links_app">

    <div class="container">
        <div class="row mb-2">
            <div class="col-md-6 col-sm-12">
                <select v-model="flipbook_id" class="form-control" v-on:change="get_list">
                    <option v-for="(option_flipbook, key_flipbook) in options_flipbook" v-bind:value="key_flipbook">{{ option_flipbook }}</option>
                </select>
            </div>
        </div>
        <div class="text-center mb-2" v-show="loading">
            <i class="fa fa-spin fa-spinner fa-3x"></i>
        </div>
        <table class="table bg-white" v-show="!loading">
            <thead>
                <th>Tema</th>
                <th width="250px">Cantidad links abiertos</th>
            </thead>
            <tbody>
                <tr v-for="(tema, tema_key) in temas">
                    <td>{{ tema.nombre_tema }}</td>
                    <td class="text-center" v-bind:class="{'table-success': tema.qty_eventos > 0 }">
                        <span>{{ tema.qty_eventos }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>    
</div>

<script>
    new Vue({
        el: '#links_app',
        created: function(){
            this.get_list();
        },
        data: {
            usuario_id: <?= $row->id ?>,
            flipbook_id: '0<?= $flipbook_id ?>',
            temas: [],
            options_flipbook: <?= json_encode($options_flipbook) ?>,
            loading: false
        },
        methods: {
            get_list: function(){
                this.loading = true
                axios.get(url_api + 'usuarios/get_actividad_links/' + this.usuario_id + '/' + this.flipbook_id)
                .then(response => {
                    this.temas = response.data.list;
                    history.pushState(null, null, url_app + 'usuarios/actividad_links/' + this.usuario_id + '/' + this.flipbook_id);
                    this.loading = false
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            //Actualizar listdo de temas al cambiar de flipbook
            update_flipbook: function(){
                axios.get(url_api + 'flipbooks/get_temas/' + this.flipbook_id)
                .then(response => {
                    this.temas = response.data.list;
                    this.tema_id = '0';
                    this.get_list();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>