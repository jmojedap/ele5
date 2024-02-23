<div id="preguntasApp">
    <div class="center_box_920">
        <div v-for="(pregunta,key) in preguntas">
            <div class="my-2">
                <a v-bind:href="`<?= URL_ADMIN . "temas/agregar_pregunta/{$row->id}/" ?>` + key + `/add`" class="btn btn-light">
                    Insertar pregunta aquí
                </a>
            </div>
            <div class="card">
                <div class="card-header">
                    Pregunta <strong class="text-primary">{{ key + 1 }}</strong> de <span>{{ preguntas.length }}</span>
                    <div class="float-right">
                        <a v-bind:href="`<?= base_url('preguntas/detalle/') ?>` + pregunta.id" class="btn btn-sm btn-light" target="_blank">
                            Ver detalle
                        </a>
                        <button class="btn btn-sm btn-light" v-on:click="moverPregunta(pregunta.id,key-1)" v-bind:disabled="key == 0">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                        <button class="btn btn-sm btn-light" v-on:click="moverPregunta(pregunta.id,key+1)" v-bind:disabled="key == preguntas.length - 1">
                            <i class="fa fa-chevron-down"></i>
                        </button>
    
                        <button
                            v-on:click="setCurrent(key)" data-toggle="modal" data-target="#delete_modal"
                            class="btn btn-sm btn-warning" title="Quitar pregunta de este tema, no se elimina"
                            >
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p v-html="pregunta.texto_pregunta"></p>
                    <p v-html="pregunta.enunciado_2"></p>
                    <div v-show="pregunta.archivo_imagen" class="text-center my-2">
                        <img
                            v-bind:src="`<?= URL_UPLOADS ?>preguntas/` + pregunta.archivo_imagen"
                            class="rounded w100pc"
                            alt="Imagen de la pregunta"
                            onerror="this.src='<?= URL_IMG ?>app/img_pregunta_nd.png'"
                        >
                    </div>

                    
                    <table class="tabla-transparente">
                        <tbody>
                            <tr>
                                <td width="30px">
                                    <span class="badge badge-primary">A</span>
                                </td>
                                <td class="">{{ pregunta.opcion_1 }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-primary">B</span></td>
                                <td class="">{{ pregunta.opcion_2 }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-primary">C</span></td>
                                <td class="">{{ pregunta.opcion_3 }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-primary">D</span></td>
                                <td class="">{{ pregunta.opcion_4 }}</td>
                            </tr>
                        </tbody>
                    </table>
    
                    <hr>
                    
                    <div class="">
                        <span class="text-muted">ID pregunta: </span>
                        <span class="text-primary">{{ pregunta.id }}</span> &middot; 
                        <span class="text-muted">Competencia: </span>
                        <span class="text-primary">{{ pregunta.competencia_id }}</span> &middot; 
                        <span class="text-muted">Creado por: </span>
                        <span class="text-primary">{{ pregunta.creado_usuario_id }}</span>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="my-2">
            <a v-bind:href="`<?= URL_ADMIN . "temas/agregar_pregunta/{$row->id}/`+ preguntas.length +`/add" ?>`" class="btn btn-light">Insertar pregunta aquí</a>
        </div>
    </div>

    <?php $this->load->view('common/bs4/modal_single_delete_v') ?>

</div>

<script>
var preguntasApp = new Vue({
    el: '#preguntasApp',
    created: function(){
        //this.get_list()
    },
    data: {
        temaId: <?= $row->id ?>,
        preguntas: <?= json_encode($preguntas->result()) ?>,
        currentPregunta: {},
        loading: false,
    },
    methods: {
        getList: function(){
            axios.get(URL_API + 'temas/get_preguntas/' + this.temaId)
            .then(response => {
                this.preguntas = response.data.list
            })
            .catch(function(error) { console.log(error) })
        },
        moverPregunta: function(preguntaId, nuevaPosicion){
            axios.get(URL_API + 'temas/mover_pregunta/' + this.temaId + '/' + preguntaId + '/' + nuevaPosicion)
            .then(response => {
                if (response.data.qty_affected > 0) {
                    toastr['info']('Pregunta movida')
                    this.preguntas = response.data.list
                }
            })
            .catch(function(error) { console.log(error) })
        },
        setCurrent: function(key){
            this.currentPregunta = this.preguntas[key]
        },
        deleteElement: function(){
            axios.get(URL_API + 'temas/quitar_pregunta/' + this.temaId + '/' + this.currentPregunta.id)
            .then(response => {
                if (response.data.qty_deleted > 0) {
                    toastr['info']('Pregunta retirada del tema')
                    this.preguntas = response.data.list
                }
            })
            .catch(function(error) { console.log(error) })
        },
    }
})
</script>