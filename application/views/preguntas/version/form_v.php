<form accept-charset="utf-8" method="POST" id="pregunta_form" @submit.prevent="send_form">        
    <div class="row mb-2">
        <div class="col-md-5 offset-md-2">
            <h4>Versión original</h4>
        </div>
        <div class="col-md-5">
            <h4>Versión propuesta</h4>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-5 offset-md-2">
            
        </div>
        <div class="col-md-5">
            <button class="btn btn-success btn-block" type="submit">
                Guardar
            </button>
        </div>
    </div>
    <div class="form-group row">
        <label for="cod_pregunta" class="col-md-2 col-form-label text-right">Cód. pregunta</label>
        <div class="col-md-5">
            <p>{{ pregunta.cod_pregunta }}</p>
        </div>
        <div class="col-md-5">
            <input
                type="text"
                id="field-cod_pregunta"
                name="cod_pregunta"
                required
                class="form-control"
                placeholder="Código pregunta"
                title="Código pregunta"
                v-model="form_values.cod_pregunta"
                >
        </div>
    </div>
    <div class="form-group row">
        <label for="texto_pregunta" class="col-md-2 col-form-label text-right">Texto pregunta</label>
        <div class="col-md-5">
            <div>
                {{ form_values.texto_pregunta }}
            </div>
        </div>
        <div class="col-md-5">
            <textarea
                type="text"
                id="field-texto_pregunta"
                name="texto_pregunta"
                required
                class="form-control"
                placeholder="Texto de la pregunta"
                title="Texto de la pregunta"
                v-model="form_values.texto_pregunta"
                rows="3"
                ></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="enunciado_2" class="col-md-2 col-form-label text-right">Enunciado complementario</label>
        <div class="col-md-5">
            <?php echo $row->enunciado_2 ?>
        </div>
        <div class="col-md-5">
            <textarea
                type="text"
                id="field-enunciado_2"
                name="enunciado_2"
                class="form-control"
                placeholder="Enunciado complementario"
                title="Enunciado complementario"
                v-model="form_values.enunciado_2"
                rows="3"
                ></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="enunciado_id" class="col-md-2 col-form-label text-right">Lectura asociada</label>
        <div class="col-md-5">
            <?php echo $this->Pcrn->campo_id('post', $row->enunciado_id, 'nombre_post'); ?>
        </div>
        <div class="col-md-5">
            <?php echo form_dropdown('enunciado_id', $options_enunciado, '', 'class="form-control chosen-select" v-model="form_values.enunciado_id"') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="opcion_1" class="col-md-2 col-form-label text-right">Opción A</label>
        <div class="col-md-5">
            {{ pregunta.opcion_1 }}
        </div>
        <div class="col-md-5">
            <input
                type="text"
                id="field-opcion_1"
                name="opcion_1"
                required
                class="form-control"
                placeholder="Opción B"
                title="Opción B"
                v-model="form_values.opcion_1"
                >
        </div>
    </div>
    <div class="form-group row">
        <label for="opcion_2" class="col-md-2 col-form-label text-right">Opción B</label>
        <div class="col-md-5">
            {{ pregunta.opcion_2 }}
        </div>
        <div class="col-md-5">
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
        <label for="opcion_3" class="col-md-2 col-form-label text-right">Opción C</label>
        <div class="col-md-5">
            {{ pregunta.opcion_3 }}
        </div>
        <div class="col-md-5">
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
        <label for="opcion_4" class="col-md-2 col-form-label text-right">Opción D</label>
        <div class="col-md-5">
            {{ pregunta.opcion_4 }}
        </div>
        <div class="col-md-5">
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
        <label for="respuesta_correcta" class="col-md-2 col-form-label text-right">Opción respuesta correcta</label>
        <div class="col-md-5">
            <?php echo $this->Item_model->nombre(57, $row->respuesta_correcta); ?>
        </div>
        <div class="col-md-5">
            <?php echo form_dropdown('respuesta_correcta', $options_letras, '', 'class="form-control" v-model="form_values.respuesta_correcta"') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="nivel" class="col-md-2 col-form-label text-right">Nivel</label>
        <div class="col-md-5">
            <?php echo $this->Item_model->nombre(3, $row->nivel, 'item_largo'); ?>
        </div>
        <div class="col-md-5">
            <?php echo form_dropdown('nivel', $options_nivel, '', 'class="form-control" v-model="form_values.nivel"') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="area_id" class="col-md-2 col-form-label text-right">Área</label>
        <div class="col-md-5">
            <?php echo $this->Item_model->nombre_id($row->area_id); ?>
        </div>
        <div class="col-md-5">
            <?php echo form_dropdown('area_id', $options_area, '', 'class="form-control" v-model="form_values.area_id"') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="competencia_id" class="col-md-2 col-form-label text-right">Competencia</label>
        <div class="col-md-5">
            <?php echo $this->Item_model->nombre_id($row->competencia_id); ?>
        </div>
        <div class="col-md-5">
            <?php echo form_dropdown('competencia_id', $options_competencia, '', 'class="form-control" v-model="form_values.competencia_id"') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="componente_id" class="col-md-2 col-form-label text-right">Componente</label>
        <div class="col-md-5">
            <?php echo $this->Item_model->nombre_id($row->componente_id); ?>
        </div>
        <div class="col-md-5">
            <?php echo form_dropdown('componente_id', $options_componente, '', 'class="form-control" v-model="form_values.componente_id"') ?>
        </div>
    </div>
</form>