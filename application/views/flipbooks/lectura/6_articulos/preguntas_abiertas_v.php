<?php
    $options_group = array();
    foreach ($this->session->arr_grupos as $grupo_id) 
    {
        $options_group['0' . $grupo_id] = $this->App_model->nombre_grupo($grupo_id);
    }
?>

<!-- Modal -->
<div class="modal fade" id="modalPreguntasAbiertas" tabindex="-1" aria-labelledby="modalPreguntasAbiertasLabel"aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form accept-charset="utf-8" method="POST" id="pregunta-abierta-form" @submit.prevent="submitPreguntaAbiertaForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPreguntasAbiertasLabel">Asigne una pregunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary mb-2" v-on:click="preguntaAbiertaPersonalizada = !preguntaAbiertaPersonalizada">
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

                    <div class="form-group" v-show="preguntaAbiertaPersonalizada">
                        <label for="texto_pregunta">Escriba una pregunta sobre el tema:</label>
                        <textarea id="field-texto_pregunta" name="contenido" class="form-control summernote_no"
                            placeholder="Escriba la pregunta" title="Escriba la pregunta"></textarea>
                        <div class="invalid-feedback">
                            El texto de la pregunta no puede estar vacío
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="grupo_id">Asignar al grupo</label>
                        <?php echo form_dropdown('grupo_id', $options_group, '00', 'class="form-select" v-model="grupoId" required v-on:change="cargarPreguntasAbiertasAsignadas"') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>