<?php
    $options_group = array();
    foreach ($this->session->arr_grupos as $grupo_id) 
    {
        $options_group['0' . $grupo_id] = $this->App_model->nombre_grupo($grupo_id);
    }
?>

<div class="center_box_750 mt-2">
    <div class="card">
        <div class="card-body">
            <div class="">
                <form accept-charset="utf-8" method="POST" id="pregunta-abierta-form" @submit.prevent="submitPreguntaAbiertaForm">
                    <h5 class="card-title" id="modalPreguntasAbiertasLabel">Asigne una pregunta</h5>
                    <div class="modal-body">
                        <button type="button" class="btn btn-primary mb-2"
                            v-on:click="preguntaAbiertaPersonalizada = !preguntaAbiertaPersonalizada">
                            <span v-show="!preguntaAbiertaPersonalizada"><i class="fa fa-arrow-left"></i> Redactar pregunta
                                propia</span>
                            <span v-show="preguntaAbiertaPersonalizada">Ver preguntas predefinidas</span>
                        </button>
    
                        <div v-show="!preguntaAbiertaPersonalizada">
                            <p>
                                Elija una pregunta para asignar a sus estudiantes:
                            </p>
                            <div class="list-group mb-2">
                                <button type="button" class="list-group-item list-group-item-action"
                                    v-for="pregunta in bookData.preguntas_abiertas"
                                    v-show='currentArticulo.tema_id == pregunta.tema_id'
                                    v-on:click="setPreguntaAbierta(pregunta.id)"
                                    v-bind:class="{active: preguntaAbiertaId == pregunta.id}">
                                    {{ pregunta.text_pregunta }}
                                </button>
                            </div>
                        </div>
    
                        <input type="hidden" name="tema_id" v-model="currentArticulo.tema_id">
                        <input type="hidden" name="referente_2_id" value="2"><!-- TIPO DE PREGUNTA ABIERTA -->
    
                        <div class="mb-2" v-show="preguntaAbiertaPersonalizada">
                            <label for="texto_pregunta">Escriba una pregunta sobre el tema:</label>
                            <textarea id="field-texto_pregunta" name="contenido" class="form-control summernote_no"
                                placeholder="Escriba la pregunta" title="Escriba la pregunta"></textarea>
                            <div class="invalid-feedback">
                                El texto de la pregunta no puede estar vacío
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="grupo_id">Asignar al grupo</label>
                            <?php echo form_dropdown('grupo_id', $options_group, '00', 'class="form-select" v-model="grupoId" required v-on:change="cargarPreguntasAbiertasAsignadas"') ?>
                        </div>
                    </div>
                    <div class="mt-2 text-end">
                        <button type="submit" class="btn btn-primary w120p me-2">Asignar</button>
                        <button type="button" v-on:click="section='pagina'" class="btn btn-light w120p">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="border-top mt-3">
                <table class="table bg-white">
                    <thead>
                        <th>Preguntas asignadas al grupo ({{ preguntasAbiertasAsignadas.length }})</th>
                    </thead>
                    <tbody>
                        <tr v-for="(preguntaAsignada, pak) in preguntasAbiertasAsignadas">
                            <td>{{ preguntaAsignada.texto_pregunta }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>