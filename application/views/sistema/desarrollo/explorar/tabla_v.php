<form id="form_seleccionados" @submit.prevent="validar_login" class="d-none">
    <input name="seleccionados" type="text" v-model="seleccionados" class="form-control">
</form>

<table class="table bg-white">
    <thead>
        <th width="20px">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" v-model="todos_seleccionados" v-on:click="seleccionar_todos" id="seleccionar_todos" class="custom-control-input">
                <label class="custom-control-label" for="seleccionar_todos">
                    <span class="text-hide">-</span>
                </label>
            </div>
        </th>
        <th width="50px">ID</th>
        <th width="35%">Nombre</th>
        <th>Documento</th>
        <th>Ciudad</th>
    </thead>
    <tbody>
        <tr v-for="usuario in usuarios">
            <td>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-bind:id="`fila_${usuario.id}`" v-model="seleccionados" v-bind:value="usuario.id">
                    <label class="custom-control-label" v-bind:for="`fila_${usuario.id}`">
                        <span class="text-hide">-</span>
                    </label>
                </div>
            </td>
            
            <td>
                {{ usuario.id }}
            </td>
            <td>
                <a v-bind:href="`${base_url}usuarios/info/${usuario.id}`">
                    {{ usuario.nombre }} {{ usuario.apellidos }}
                </a>
            </td>
            <td>
                {{ usuario.no_documento }}
            </td>
            <td>
                {{ usuario.ciudad_id }}
            </td>
        </tr>
    </tbody>
</table>

