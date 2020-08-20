<?php
    $cl_col['title'] = '';
    $cl_col['type'] = 'only-lg';
    $cl_col['creado'] = 'only-lg';
    $cl_col['usuario'] = 'only-lg';
    $cl_col['institucion'] = 'only-lg';
?>

<div class="table table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th class="<?php echo $cl_col['type'] ?>">Tipo</th>
            <th class="<?php echo $cl_col['title'] ?>">Nombre</th>
            <th class="<?php echo $cl_col['creado'] ?>">Fecha creado</th>
            <th class="<?php echo $cl_col['usuario'] ?>">Creado por</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                
                <td class="<?php echo $cl_col['type'] ?>">
                    {{ element.tipo_id | type_name }}
                </td>
                <td class="<?php echo $cl_col['title'] ?>">
                    <a v-bind:href="`<?php echo base_url("eventos/info/") ?>` + element.id">
                        {{ element.nombre_evento }}
                    </a>
                </td>
                <td class="<?php echo $cl_col['creado'] ?>">
                    {{ element.creado }}
                </td>

                <td class="<?php echo $cl_col['usuario'] ?>">
                    <a v-bind:href="`<?php echo base_url("usuarios/actividad/") ?>` + element.usuario_id">
                        {{ element.username }}
                    </a>
                    <br>
                    {{ element.nombre_institucion }} &middot; ID Grupo asociado: {{ element.grupo_id }}
                </td>
                
                <td>
                    <button class="btn btn-light btn-sm w31p" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>