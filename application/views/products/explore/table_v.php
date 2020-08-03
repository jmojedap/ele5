<?php
    //Clases columnas
        $cl_col['id'] = '';
        $cl_col['name'] = '';
        $cl_col['price'] = '';
        $cl_col['description'] = 'only-lg';
?>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="46px">
                <input type="checkbox" id="checkbox_all_selected" @click="select_all" v-model="all_selected">
            </th>
            <th class="<?php echo $cl_col['name'] ?>">Nombre</th>
            <th class="<?php echo $cl_col['price'] ?>">Precio</th>
            <th class="<?php echo $cl_col['description'] ?>">
                Descripción
            </th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                
                <td class="<?php echo $cl_col['name'] ?>">
                    <a v-bind:href="`<?php echo base_url("products/info/") ?>` + element.id">
                        {{ element.name }}
                    </a>
                </td>

                <td class="<?php echo $cl_col['price'] ?>">
                    {{ element.price | currency }}
                </td>

                <td class="<?php echo $cl_col['description'] ?>">
                    {{ element.description }}
                </td>
                
                <td>
                    <button class="btn btn-light btn-sm btn-sm-square" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>