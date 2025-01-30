<form accept-charset="utf-8" method="POST" id="pregunta_form" @submit.prevent="send_form">        
    <fieldset v-bind:disabled="loading">
        <input type="hidden" name="nivel" value="<?= $row->nivel ?>">
        <input type="hidden" name="area_id" value="<?= $row->area_id ?>">
        <input type="hidden" name="tipo_pregunta_id" value="15">
        <input type="hidden" name="archivo_imagen" id="field-archivo_imagen" value="">
        <input type="hidden" name="creado_usuario_id" id="field-creado_usuario_id" value="<?= $this->session->userdata('usuario_id') ?>">
        <div class="form-group row">
            <div class="col-md-8 offset-md-4">
                <button class="btn btn-success btn-block" type="submit">
                    <span v-show="loading"><i class="fa fa-spin fa-spinner"></i></span> Guardar
                </button>
            </div>
        </div>
        <div class="form-group row">
            <label for="texto_pregunta" class="col-md-4 col-form-label text-right">Texto pregunta</label>
            <div class="col-md-8">
                <textarea name="texto_pregunta" class="summernote"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="enunciado_2" class="col-md-4 col-form-label text-right">Enunciado complementario</label>
            <div class="col-md-8">
                <textarea
                    name="enunciado_2"
                    class="form-control"
                    placeholder="Enunciado complementario"
                    title="Enunciado complementario"
                    rows="3"
                    ></textarea>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="opcion_1" class="col-md-4 col-form-label text-right">A) Nivel básico</label>
            <div class="col-md-8">
                <textarea
                    rows="3"
                    id="field-opcion_1"
                    name="opcion_1"
                    required
                    class="form-control"
                    title="Nivel básico"
                    v-model="form_values.opcion_1"
                    ></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="opcion_2" class="col-md-4 col-form-label text-right">B) Nivel medio</label>
            <div class="col-md-8">
                <textarea
                    rows="3"
                    id="field-opcion_2"
                    name="opcion_2"
                    required
                    class="form-control"
                    title="Nivel medio"
                    v-model="form_values.opcion_2"
                    ></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="opcion_3" class="col-md-4 col-form-label text-right">C) Nivel avanzado</label>
            <div class="col-md-8">
                <textarea
                    rows="3"
                    id="field-opcion_3"
                    name="opcion_3"
                    required
                    class="form-control"
                    title="Nivel avanzado"
                    v-model="form_values.opcion_3"
                    ></textarea>
            </div>
        </div>

        <hr>

        <div class="mb-3 row">
            <label for="habilidad" class="col-md-4 col-form-label text-end text-right">Habilidad</label>
            <div class="col-md-8">
                <select name="habilidad" v-model="form_values.habilidad" class="form-select form-control" required>
                    <option v-for="optionHabilidad in arrHabilidades" v-bind:value="optionHabilidad.name">{{ optionHabilidad.name }}</option>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="proceso_pensamiento" class="col-md-4 col-form-label text-end text-right">Proceso de pensamiento</label>
            <div class="col-md-8">
                <select name="proceso_pensamiento" v-model="form_values.proceso_pensamiento" class="form-select form-control" required>
                    <option v-for="optionProceso in arrProcesos" v-bind:value="optionProceso.name">{{ optionProceso.name }}</option>
                </select>
            </div>
        </div>
        
    </fieldset>
</form>