<script src="<?php echo URL_RESOURCES ?>assets/sortablejs/Sortable.js"></script>

<?php
    //Establecer valores iniciales
    $nombre_cuestionario = 'Cuestionario ' . $this->session->userdata('username') . ' (' . date('d/M') . ')';

    $nivel = '';
    if ( $preguntas->result() > 0 ) { $nivel = '0' . $preguntas->row()->nivel; }

    $area_id = '';
    if ( $preguntas->result() > 0 ) { $area_id = '0' . $preguntas->row()->area_id; }  
?>

<?php $this->load->view('preguntas/selectorp/jquery_script_v') ?>

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
                </tbody>
            </table>

            <div class="card mb-2">
                <div class="card-body">
                    <input type="checkbox" name="show_detail" v-model="show_detail">
                    <label for="show_detail">Mostrar detalle de preguntas</label>
                </div>
            </div>

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
                                <div class="btn btn-light btn-sm btn-sm-square sortable_handle">
                                    <i class="fas fa-arrows-alt"></i>
                                </div>
                                <button class="btn btn-light btn-sm btn-sm-square" v-on:click="delete_element(row_key)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <p>
                            <span class="etiqueta nivel w1">{{ pregunta.nivel }}</span>
                            <span class="etiqueta_a" v-bind:class="`etiqueta_a` + pregunta.area_id">
                                {{ pregunta.area_id | area_name }}
                            </span>
                            <div class="progress mb-2" v-if="pregunta.qty_answers > 0" style="height: 2px;">
                                <div class="progress-bar" v-bind:class="pregunta.difficulty | difficulty_class" role="progressbar" v-bind:style="`width: ` + pregunta.difficulty + `%`" v-bind:aria-valuenow="pregunta.difficulty" aria-valuemin="0" aria-valuemax="100">

                                </div>
                            </div>
                        </p>
                        <p v-html="pregunta.texto_pregunta"></p>
                        <div id="pregunta_detail" class="mb-2" v-show="show_detail">
                            <p v-html="pregunta.enunciado_2"></p>
                            <img v-if="pregunta.archivo_imagen" v-bind:src="pregunta.url_imagen_pregunta" alt="Imagen pregunta" class="img-thumbnail mb-2">
                            <ul style="list-style-type:none;">
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 1 }"><span class="badge badge-secondary">A</span> {{ pregunta.opcion_1 }}</li>
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 2 }"><span class="badge badge-secondary">B</span> {{ pregunta.opcion_2 }}</li>
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 3 }"><span class="badge badge-secondary">C</span> {{ pregunta.opcion_3 }}</li>
                                <li v-bind:class="{'right_answer': pregunta.respuesta_correcta == 4 }"><span class="badge badge-secondary">D</span> {{ pregunta.opcion_4 }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Vue App -->
<?php $this->load->view('preguntas/selectorp/vue_v') ?>

<!-- Sortable Preguntas Script -->
<script>
    var sortable_preguntas = document.getElementById('preguntas');
    
    new Sortable(sortable_preguntas, {
        handle: '.sortable_handle', // handle class
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