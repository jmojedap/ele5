<?php
    $cl_col['title'] = '';
    $cl_col['open'] = '';
    $cl_col['area'] = 'd-none d-md-table-cell d-lg-table-cell';
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
            <th class="<?php echo $cl_col['open'] ?>"></th>
            <th class="<?php echo $cl_col['title'] ?>">Texto Pregunta</th>
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
                <td class="<?php echo $cl_col['open'] ?>">
                    <a v-bind:href="`<?php echo base_url("preguntas/index/") ?>` + element.client_id" class="btn btn-primary btn-sm">
                        Abrir
                    </a>
                </td>
                <td class="<?php echo $cl_col['title'] ?>" v-html="element.texto_pregunta"></td>
                
                <td>
                    <button class="btn btn-light btn-sm w31p" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>