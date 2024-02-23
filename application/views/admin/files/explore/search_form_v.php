<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div>
            <label for="u">Id usuario creador</label>
            <input
            name="u" type="number" class="form-control"
            title="ID usuario creador" placeholder="ID usuario creador"
            v-model="filters.u"
            >
        </div>
        <div>
            <label for="fe3">Colección</label>
            <select name="fe3" v-model="filters.fe3" class="form-select form-control">
                <option value="">[ Todos ]</option>
                <option v-for="optionFileCollection in arrFileCollections" v-bind:value="optionFileCollection.cod">{{ optionFileCollection.name }}</option>
            </select>
        </div>
        
        <!-- Botón ejecutar y limpiar filtros -->
        <div>
            <label for="" style="opacity: 0%">Enviar</label><br>
            <button class="btn btn-primary w100p" type="submit">Buscar</button>
            <button type="button" class="btn btn-light" title="Quitar los filtros de búsqueda"
                v-show="strFilters.length > 0" v-on:click="clearFilters">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
</form>
