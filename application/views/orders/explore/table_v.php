<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['status_icon'] = '';
        $cl_col['status'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['title'] = '';
        $cl_col['user'] = '';
        $cl_col['amount'] = '';
        $cl_col['dates'] = 'd-none d-md-table-cell d-lg-table-cell';
?>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="46px">
                <div class="form-check abc-checkbox abc-checkbox-primary">
                    <input class="form-check-input" type="checkbox" id="checkbox_all_selected" @click="select_all" v-model="all_selected">
                    <label class="form-check-label" for="checkbox_all_selected"></label>
                </div>
            </th>
            <th class="<?php echo $cl_col['status_icon'] ?>" width="10px"></th>
            <th class="<?php echo $cl_col['status'] ?>">Estado</th>
            <th class="<?php echo $cl_col['title'] ?>">Ref. venta</th>
            <th class="<?php echo $cl_col['user'] ?>">Comprador</th>
            <th class="<?php echo $cl_col['amount'] ?>">Valor</th>
            <th class="<?php echo $cl_col['dates'] ?>"></th>
            
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
                    
                <td class="<?php echo $cl_col['status_icon'] ?>">
                    <i class="fa fa-check-circle text-success" v-if="element.status == 1"></i>
                    <i class="fa fa-exclamation-triangle text-warning" v-if="element.status == 5"></i>
                    <i class="far fa-circle text-muted" v-if="element.status == 10"></i>
                </td>
                <td class="<?php echo $cl_col['status'] ?>">
                    {{ element.status | status_name  }}
                </td>
                <td class="<?php echo $cl_col['title'] ?>">
                    <a v-bind:href="`<?php echo base_url("orders/info/") ?>` + element.id">
                        {{ element.order_code }}
                    </a>
                </td>

                <td class="<?php echo $cl_col['user'] ?>">
                    <a v-bind:href="`<?php echo base_url("users/profile/") ?>` + element.user_id">
                        {{ element.buyer_name }}
                    </a>
                    <br>
                    {{ element.email }}
                </td>
                <td class="<?php echo $cl_col['amount'] ?>">
                    {{ element.amount | currency }}
                </td>
                <td class="<?php echo $cl_col['dates'] ?>">
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