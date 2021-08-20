<form accept-charset="utf-8" method="POST" id="pregunta_form" @submit.prevent="send_form">        
    <fieldset v-bind:disabled="loading">
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
            <label for="opcion_1" class="col-md-4 col-form-label text-right">Opción A</label>
            <div class="col-md-8">
                <input
                    type="text"
                    id="field-opcion_1"
                    name="opcion_1"
                    required
                    class="form-control"
                    placeholder="Opción A"
                    title="Opción A"
                    v-model="form_values.opcion_1"
                    >
            </div>
        </div>
        <div class="form-group row">
            <label for="opcion_2" class="col-md-4 col-form-label text-right">Opción B</label>
            <div class="col-md-8">
                <input
                    type="text"
                    id="field-opcion_2"
                    name="opcion_2"
                    required
                    class="form-control"
                    placeholder="Opción B"
                    title="Opción B"
                    v-model="form_values.opcion_2"
                    >
            </div>
        </div>
        <div class="form-group row">
            <label for="opcion_3" class="col-md-4 col-form-label text-right">Opción C</label>
            <div class="col-md-8">
                <input
                    type="text"
                    id="field-opcion_3"
                    name="opcion_3"
                    required
                    class="form-control"
                    placeholder="Opción C"
                    title="Opción C"
                    v-model="form_values.opcion_3"
                    >
            </div>
        </div>
        <div class="form-group row">
            <label for="opcion_4" class="col-md-4 col-form-label text-right">Opción D</label>
            <div class="col-md-8">
                <input
                    type="text"
                    id="field-opcion_4"
                    name="opcion_4"
                    required
                    class="form-control"
                    placeholder="Opción D"
                    title="Opción D"
                    v-model="form_values.opcion_4"
                    >
            </div>
        </div>
        <div class="form-group row">
            <label for="respuesta_correcta" class="col-md-4 col-form-label text-right">Opción respuesta correcta</label>
            <div class="col-md-8">
                <?php echo form_dropdown('respuesta_correcta', $options_letras, '', 'class="form-control" v-model="form_values.respuesta_correcta"') ?>
            </div>
        </div>
    </fieldset>
</form>