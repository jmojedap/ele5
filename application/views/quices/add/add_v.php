<div id="addQuiz">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="quiz_form" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <div class="form-group row">
                    <label for="nombre_quiz" class="col-md-4 col-form-label text-right">Título *</label>
                    <div class="col-md-8">
                        <input
                            class="form-control" name="nombre_quiz"
                            required autofocus
                            v-model="fields.nombre_quiz">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="tipo_quiz_id" class="col-md-4 col-form-label text-end text-right">Tipo *</label>
                    <div class="col-md-8">
                        <select name="tipo_quiz_id" v-model="fields.tipo_quiz_id" class="form-select form-control" required>
                            <option v-for="optionTipo in arrTipos" v-bind:value="optionTipo.cod">{{ optionTipo.short_name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="area_id" class="col-md-4 col-form-label text-end text-right">Área *</label>
                    <div class="col-md-8">
                        <select name="area_id" v-model="fields.area_id" class="form-select form-control" required>
                            <option v-for="optionArea in arrAreas" v-bind:value="optionArea.id">{{ optionArea.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="nivel" class="col-md-4 col-form-label text-end text-right">Nivel *</label>
                    <div class="col-md-8">
                        <select name="nivel" v-model="fields.nivel" class="form-select form-control" required>
                            <option v-for="optionNivel in arrNiveles" v-bind:value="optionNivel.cod">{{ optionNivel.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8 col-sm-12">
                        <button class="btn btn-success w120p" type="submit">
                            Crear
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_created" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Evidencia creada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    Evidencia creada correctamente
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="goToCreated">
                        Abrir evidencia
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="clearForm" data-dismiss="modal">
                        <i class="fa fa-plus"></i>
                        Crear otra
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('quices/add/vue_v');