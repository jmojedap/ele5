<div class="text-center" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x"></i>
</div>

<p v-show="!loading">
    {{ search_num_rows }} resultados
</p>

<div class="table-responsive" v-show="!loading">
    <table class="table bg-white">
        <thead>
            <th width="46px">
                <div class="form-check abc-checkbox abc-checkbox-primary">
                    <input class="form-check-input" type="checkbox" id="checkbox_all_selected" @click="select_all" v-model="all_selected">
                    <label class="form-check-label" for="checkbox_all_selected"></label>
                </div>
            </th>
            <th>Ref. venta</th>
            <th width="10px"></th>
            <th >Estado</th>
            <th>Comprador</th>
            <th>Nivel - Institución</th>
            <th>Valor</th>
            <th></th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <div class="form-check abc-checkbox abc-checkbox-primary">
                        <input class="form-check-input" type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                        <label class="form-check-label" v-bind:for="`check_` + element.id"></label>
                    </div>
                </td>
                    
                <td>
                    <a v-bind:href="`<?php echo base_url("orders/info/") ?>` + element.id">
                        {{ element.order_code }}
                    </a>
                </td>
                <td>
                    <i class="fa fa-check-circle text-success" v-if="element.status == 1"></i>
                    <i class="fa fa-exclamation-triangle text-warning" v-if="element.status == 5"></i>
                    <i class="far fa-circle text-muted" v-if="element.status == 10"></i>
                </td>
                <td >
                    {{ element.status | status_name  }}
                </td>

                <td>
                    <strong class="text-primary">
                        {{ element.buyer_name }}
                    </strong>
                    &middot;
                    <span class="text-muted">CC/NIT {{ element.id_number }}</span>
                    <br>
                    {{ element.email }}
                    
                </td>
                <td>
                    <span class="etiqueta nivel w1">{{ element.level | nivel_name }}</span>
                    <a v-bind:href="`<?php echo base_url("instituciones/index/") ?>` + element.institution_id" class="">
                        {{ element.institution_name }}
                    </a>
                </td>
                <td class="text-right">
                    {{ element.amount | currency }}
                </td>
                <td>
                    <b>A</b> <span v-bind:title="element.updated_at">{{ element.updated_at | ago }}</span>
                    <br>
                    <b>C</b> <span v-bind:title="element.created_at"> {{ element.created_at | ago }}</span>
                </td>
                
                <td>
                    <button class="btn btn-light btn-sm btn-square" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>