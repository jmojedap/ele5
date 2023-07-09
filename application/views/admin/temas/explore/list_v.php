<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="selectAll" v-model="allSelected">
            </th>
            <th width="10px" class="table-warning">ID</th>
            <th width="100px">Código</th>
            <th>Tema</th>
            <th>Nivel - Área</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td class="table-warning">{{ element.id }}</td>
                <td>{{ element.cod_tema }}</td>
                    
                <td>
                    <a v-bind:href="`<?= URL_ADMIN ?>temas/index/` + element.id">
                        {{ element.nombre_tema }}
                    </a>
                    <br>
                    <span class="text-muted">Tipo: </span>{{ tipoName(element.tipo_id)  }}
                </td>
                <td>
                    <span class="etiqueta nivel w2">{{ element.nivel }}</span>
                    <span class="etiqueta" v-bind:class="`bg-area-` + element.area_id">{{ areaName(element.area_id) }}</span>
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="setCurrent(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>