<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div>
            <select name="type" v-model="filters.type" class="form-control">
                <option value="">[ Todos los tipos ]</option>
                <option v-for="optionTipo in arrTipo" v-bind:value="optionTipo.cod">{{ optionTipo.name }}</option>
            </select>
        </div>
        
        <!-- Botón ejecutar y limpiar filtros -->
        <div>
            <button class="btn btn-primary w100p" type="submit">Buscar</button>
            <button type="button" class="btn btn-light" title="Quitar los filtros de búsqueda"
                v-show="strFilters.length > 0" v-on:click="clearFilters">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
</form>
