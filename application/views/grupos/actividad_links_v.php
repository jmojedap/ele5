<?php
    foreach( $flipbooks->result() as $row_flipbook ) {
        $opciones_flipbook['0' . $row_flipbook->flipbook_id] = $this->App_model->nombre_flipbook($row_flipbook->flipbook_id);
    }
?>

<div id="links_app">

    <div class="container">
        <div class="row mb-2">
            <div class="col-md-6 col-sm-12">
                <?= form_dropdown('flipbook_id', $opciones_flipbook, $flipbook_id, 'class="form-control" v-model="flipbook_id" v-on:change="update_flipbook"') ?>
            </div>
            <div class="col-md-6 col-sm-12">
                <select name="tema_id" id="" v-model="tema_id" class="form-control" v-on:change="get_list">
                    <option value="0"> >> Todos los temas</option>
                    <option v-for="(tema, tema_key) in temas" v-bind:value="`0` + tema.id">{{ tema.nombre_tema }}</option>
                </select>
            </div>
        </div>
        <table class="table bg-white">
            <thead>
                <th>Estudiante</th>
                <th width="250px">Cantidad links abiertos</th>
            </thead>
            <tbody>
                <tr v-for="(estudiante, estudiante_key) in estudiantes">
                    <td>{{ estudiante.display_name }}</td>
                    <td class="text-center" v-bind:class="{'table-success': estudiante.qty_eventos > 0 }">
                        <span v-show="estudiante.qty_eventos > 0">{{ estudiante.qty_eventos }}</span>
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
            grupo_id: <?= $row->id ?>,
            flipbook_id: '0<?= $flipbook_id ?>',
            estudiantes: [],
            tema_id: '<?= $tema_id ?>',
            temas: <?= json_encode($temas->result()) ?>,
        },
        methods: {
            get_list: function(){
                axios.get(url_api + 'grupos/get_actividad_links/' + this.grupo_id + '/' + this.flipbook_id + '/' + this.tema_id)
                .then(response => {
                    this.estudiantes = response.data.list;
                    history.pushState(null, null, url_app + 'grupos/actividad_links/' + this.grupo_id + '/' + this.flipbook_id + '/' + this.tema_id);
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