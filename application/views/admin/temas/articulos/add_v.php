<div class="card" v-show="section=='add'">
    <div class="card-header">
        Nuevo artículo para el tema
    </div>
    <div class="card-body">
        <form accept-charset="utf-8" method="POST" id="addArticuloForm" @submit.prevent="handleSubmit">
            <fieldset v-bind:disabled="loading">
                <input type="hidden" name="tipo_id" value="126">
                <input type="hidden" name="referente_1_id" value="<?= $row->id ?>">
                <input type="hidden" name="referente_2_id" value="<?= $row->area_id ?>">
                <input type="hidden" name="integer_1" value="<?= $row->nivel ?>">
        
                <div class="mb-3 row">
                    <label for="nombre_post" class="col-md-4 col-form-label text-right">Título artículo</label>
                    <div class="col-md-8">
                        <input
                            name="nombre_post" type="text" class="form-control"
                            required
                            title="Título"
                            v-model="fields.nombre_post"
                        >
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        <button class="btn btn-light w120p" type="button" v-on:click="section='list'">Cancelar</button>
                    </div>
                </div>
            <fieldset>
        </form>
    </div>
</div>
