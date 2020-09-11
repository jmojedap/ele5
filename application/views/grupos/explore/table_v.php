<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px" class="">
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th class="table-warning" width="10px">ID</th>
            <th width="120px">Grupo</th>
            <th>Institución</th>
            <th class="only-lg">Estudiantes</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td class="">
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>

                <td class="table-warning">{{ element.id }}</td>
                    
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url("grupos/index") ?>/` + element.id" class="btn btn-primary btn-sm w100p">
                        {{ element.nombre_grupo }}
                    </a>
                </td>

                <td class="only-lg">
                    <a v-bind:href="`<?php echo base_url("instituciones/grupos/") ?>` + element.institucion_id">
                        {{ element.nombre_institucion }}
                    </a>
                </td>
                <td class="only-lg">
                    {{ element.qty_students }}
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