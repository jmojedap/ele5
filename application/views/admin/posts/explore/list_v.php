<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="selectAll" v-model="allSelected">
            </th>
            <th width="10px" class="table-warning">ID</th>
            <th width="50px"></th>
            <th>Post</th>

            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td class="table-warning">{{ element.id }}</td>
                    

                <td>
                    <img
                        v-bind:src="element.url_thumbnail"
                        class="rounded w50p"
                        alt="imagen post"
                        onerror="this.src='<?= URL_IMG ?>app/sm_nd_square.png'"
                    >
                </td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN ?>posts/info/` + element.id">
                        {{ element.nombre_post }}
                        <span v-show="element.nombre_post.length == 0">Post {{ element.id }}</span>
                    </a>
                    <br>
                    <span class="text-muted">Tipo: </span>{{ typeName(element.tipo_id)  }}
                    <br>
                    <div class="only-lg">{{ element.resumen }}</div>
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