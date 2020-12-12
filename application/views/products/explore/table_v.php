<div class="text-center" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x"></i>
</div>

<div class="table-responsive" v-show="!loading">
    <table class="table bg-white">
        <thead>
            <th width="46px">
                <input type="checkbox" id="checkbox_all_selected" @click="select_all" v-model="all_selected">
            </th>
            <th class="table-warning">ID</th>
            <th>Ref.</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>
                Descripción
            </th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>

                <td class="table-warning">{{ element.id }}</td>

                <td>{{ element.code }}</td>
                
                <td>
                    <a v-bind:href="`<?= base_url("products/info/") ?>` + element.id">
                        {{ element.name }}
                    </a>
                </td>

                <td width="120px">
                    <span class="price_label">
                        {{ element.price | currency }}
                    </span>
                </td>

                <td>
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