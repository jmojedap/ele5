<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="select_all" v-model="all_selected">
            </th>
            <th width="10px">
                ID
            </th>
            <th width="200px">Tipo</th>
            <th>Publicación</th>

            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>

                <td>{{ element.id }}</td>
                    
                <td>
                    {{ element.tipo_id | type_name  }}
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url("posts/info/") ?>` + element.id">
                        {{ element.nombre_post }}
                    </a>
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>