<script src="<?php echo URL_RESOURCES ?>assets/sortablejs/Sortable.js"></script>

<?php
    //Establecer valores iniciales
    $nombre_cuestionario = 'Cuestionario ' . $this->session->userdata('username') . ' (' . date('d/M') . ')';

    $nivel = '';
    if ( $preguntas->result() > 0 ) { $nivel = '0' . $preguntas->row()->nivel; }

    $area_id = '';
    if ( $preguntas->result() > 0 ) { $area_id = '0' . $preguntas->row()->area_id; }  
?>

<style>
    .handle{
        cursor: move;
    }

    .card-ghost{
        border: 1px solid #81d4fa;
        background-color: #e1f5fe;
    }

    #pregunta_detail li {
        margin-right: 20px;
        border: 1px solid red;
        padding: 2px;
        margin-bottom: 2px;
        border-radius: 3px 3px 3px 3px;
        -moz-border-radius: 3px 3px 3px 3px;
        -webkit-border-radius: 3px 3px 3px 3px;
        border: 0px solid #000000;
    }

    #pregunta_detail li.right_answer{
        color: #FFFFFF;
        background-color: #81d4fa;
        border: 1px solid #4fc3f7;
    }

</style>


<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var str_preguntas = '<?php echo $str_preguntas ?>';

// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#selector_form').submit(function(){
            //console.log('enviando');
            create_cuestionario();
            return false;
        });
    });

// Functions
//-----------------------------------------------------------------------------
    function create_cuestionario(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'cuestionarios/selectorp_create',
            data: $('#selector_form').serialize(),
            success: function(response){
                console.log(response.cuestionario_id);
                if ( response.cuestionario_id > 0 ) {
                    window.location = base_url + 'cuestionarios/asignar/' + response.cuestionario_id
                }
            }
        });
    }

</script>

<a class="btn btn-secondary" href="<?php echo base_url('preguntas/explorar') ?>">
    <i class="fa fa-arrow-left"></i>
    Más preguntas
</a>

<div id="app_selectorp">
    <h3>Construyendo cuestionario</h3>
    <div class="row">
        <div class="col-md-4">
            <table class="table bg-white">
                <thead>
                    <th width="45%">Resumen</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>Preguntas seleccionadas</td>
                        <td>{{ list.length }}</td>
                    </tr>
                    <tr>
                        <td>Dificultad total</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" v-bind:class="avg_difficulty | difficulty_class" role="progressbar" v-bind:style="`width: ` + avg_difficulty + `%`" v-bind:aria-valuenow="avg_difficulty" aria-valuemin="0" aria-valuemax="100">
                                    {{ avg_difficulty }} &middot;
                                    {{ avg_difficulty | difficulty_name }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Ocultar detalle preguntas</td>
                        <td>
                            <input type="checkbox" name="hide_detail" v-model="hide_detail">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="card" v-show="list.length > 0">
                <div class="card-header">
                    <i class="fa fa-plus"></i>
                    Generar cuestionario
                </div>
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="selector_form">
                        <div class="form-group row">
                            <label for="nombre_cuestionario" class="col-md-4 col-form-label text-right">Nombre</label>
                            <div class="col-md-8">
                                <input
                                    type="text"
                                    id="field-nombre_cuestionario"
                                    name="nombre_cuestionario"
                                    required
                                    autofocus
                                    class="form-control"
                                    placeholder="Nombre cuestionario"
                                    title="Nombre cuestionario"
                                    value="<?php echo $nombre_cuestionario ?>"
                                    >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nivel" class="col-md-4 col-form-label text-right">Nivel</label>
                            <div class="col-md-8">
                                <?php echo form_dropdown('nivel', $options_nivel, $nivel, 'class="form-control"') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="area_id" class="col-md-4 col-form-label text-right">Área</label>
                            <div class="col-md-8">
                                <?php echo form_dropdown('area_id', $options_area, '051', 'class="form-control"') ?>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="offset-md-4 col-md-8">
                                <button class="btn btn-success w3">
                                    Crear
                                </button>
                            </div>
                        </div>
                        <input type="text" class="d-none" name="str_preguntas" id="field-str_preguntas" value="<?php echo $str_preguntas ?>">

                    </form>
                </div>
            </div>

        </div>


        <div class="col-md-8">
            <div id="preguntas" class="sortable">
                <div class="card mb-1 mw750p" v-for="(pregunta, row_key) in list" v-bind:id="`pregunta_` + pregunta.id">
                    <div class="card-body">
                        <div class="float-right">
                            <div class="">
                                <div class="btn btn-light btn-sm handle">
                                    <i class="fas fa-arrows-alt"></i>
                                </div>
                                <button class="btn btn-light btn-sm" v-on:click="delete_element(row_key)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <p v-html="pregunta.texto_pregunta"></p>
                        <div id="pregunta_detail" class="mb-2" v-show="!hide_detail">
                            <p v-html="pregunta.enunciado_2"></p>
                            <img v-if="pregunta.archivo_imagen" v-bind:src="pregunta.url_imagen_pregunta" alt="Imagen pregunta" class="img-thumbnail mb-2">
                            <ul style="list-style-type:none;">
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 1 }">A) {{ pregunta.opcion_1 }}</li>
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 2 }">B) {{ pregunta.opcion_2 }}</li>
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 3 }">C) {{ pregunta.opcion_3 }}</li>
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 4 }">D) {{ pregunta.opcion_4 }}</li>
                            </ul>
                        </div>
                        <div class="progress mb-2" v-if="pregunta.qty_answers > 0" style="height: 2px;">
                            <div class="progress-bar" v-bind:class="pregunta.difficulty | difficulty_class" role="progressbar" v-bind:style="`width: ` + pregunta.difficulty + `%`" v-bind:aria-valuenow="pregunta.difficulty" aria-valuemin="0" aria-valuemax="100">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filtros
//-----------------------------------------------------------------------------
    Vue.filter('difficulty_class', function (value) {
        if (!value) return '';
        new_value = 'bg-success';
        if ( value > 20 ) { new_value = 'bg-info'; }
        if ( value > 40 ) { new_value = 'bg-warning'; }
        if ( value > 60 ) { new_value = 'bg-danger'; }
        return new_value;
    });

    Vue.filter('difficulty_name', function (value) {
        if (!value) return '';
        new_value = 'Baja';
        if ( value > 20 ) { new_value = 'Normal'; }
        if ( value > 40 ) { new_value = 'Media'; }
        if ( value > 60 ) { new_value = 'Alta'; }
        return new_value;
    });

// VueApp
//-----------------------------------------------------------------------------
    new Vue({
        el: '#app_selectorp',
        data: {
            list: <?php echo json_encode($preguntas->result()) ?>,
            row_id: 0,
            row_key: 0,
            avg_difficulty: <?php echo $avg_difficulty ?>,
            hide_detail: true
        },
        methods: {
            delete_element: function(row_key){
                this.row_key = row_key;
                this.row_id = this.list[this.row_key].id;
                console.log('eliminado' + this.row_id);

                axios.get(app_url + 'preguntas/selectorp_remove/' + this.row_id)
                .then(response => {
                    console.log(response.data.status)
                    this.list = response.data.preguntas;
                    this.avg_difficulty = response.data.avg_difficulty;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>

<!-- Sortable Preguntas Script -->
<script>
    var sortable_preguntas = document.getElementById('preguntas');
    
    new Sortable(sortable_preguntas, {
        handle: '.handle', // handle class
        animation: 200,
        ghostClass: 'card-ghost',
        // Called when dragging element changes position
        onEnd: function(/**Event*/evt) {
            str_preguntas = '';
            $('#preguntas > div').each(function (index, element) {
                str_preguntas += element.id.replace('pregunta_', '') + ',';
            });
            str_preguntas = str_preguntas.substr(0, str_preguntas.length - 1);
            $('#field-str_preguntas').val(str_preguntas);
        }
    });
</script>

