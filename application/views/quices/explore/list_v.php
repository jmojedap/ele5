<?php
    $cl_col['checkbox'] = '';
    if ( $this->session->userdata('role') > 2 ) $cl_col['checkbox'] = 'd-none';
?>

<div class="text-center" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x"></i>
</div>

<div class="table-responsive" v-show="!loading">
    <table class="table bg-white">
        <thead>
            <th width="46px" class="<?= $cl_col['checkbox'] ?>">
                <input type="checkbox" id="checkbox_all_selected" @click="select_all" v-model="all_selected">
            </th>
            <th class="table-warning text-center" width="46px">ID</th>
            <th>Nombre</th>
            <th>Nivel - Área</th>
            <th>Cant. elementos</th>
            <th>Vista previa</th>
            <th>Tipo</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td class="<?= $cl_col['checkbox'] ?>">
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>

                <td class="table-warning text-right">{{ element.id }}</td>
                
                <td>
                    <a v-bind:href="`<?= base_url("quices/index/") ?>` + element.id">
                        {{ element.nombre_quiz }}
                    </a>
                    <br>
                </td>

                <td>
                    <span class="etiqueta nivel w1">{{ element.nivel }}</span>
                    <span class="w3 etiqueta_a" v-bind:class="`etiqueta_a` + element.area_id">
                        {{ element.area_id | area_name }}
                    </span>
                </td>

                <td>
                    {{ element.cant_elementos }}
                </td>

                <td>
                    <a v-bind:href="`<?= base_url("quices/resolver/") ?>` + element.id" class="btn btn-info btn-sm" target="_blank">
                        Abrir
                    </a>
                </td>

                <td>{{ element.tipo_quiz_id | tipo_name }} &middot; <span class="text-muted">{{ element.tipo_quiz_id }}</span></td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>