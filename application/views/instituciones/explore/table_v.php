<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px" class="d-none">
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th class="table-warning" width="10px">ID</th>
            <th width="10px">Cód</th>
            <th>Institución</th>
            <th class="only-lg">Información</th>
            <th class="only-lg">Ejecutivo</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td class="d-none">
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>

                <td class="table-warning">{{ element.id }}</td>
                <td>{{ element.cod }}</td>
                    
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url("instituciones/index") ?>/` + element.id">
                        {{ element.nombre_institucion }}
                    </a>
                </td>

                <td class="only-lg">
                    <a v-bind:href="`<?php echo base_url("instituciones/explorar/1/?f1=") ?>` + element.lugar_id">
                        {{ element.ciudad }}
                    </a>
                </td>
                <td class="only-lg">
                    <a v-bind:href="`<?php echo base_url("instituciones/explorar/1/?u=") ?>` + element.ejecutivo_id">
                        {{ element.ejecutivo }}
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