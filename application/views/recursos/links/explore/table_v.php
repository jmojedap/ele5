<?php
    $cl_col['title'] = '';
    $cl_col['open'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['tema'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['info'] = 'd-none d-md-table-cell d-lg-table-cell';
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
            <th class="<?php echo $cl_col['open'] ?>" width="80px"></th>
            <th class="<?php echo $cl_col['title'] ?>">Link</th>
            <th class="<?php echo $cl_col['tema'] ?>">Tema</th>
            <th class="<?php echo $cl_col['info'] ?>">Detalles</th>
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
                    <a v-bind:href="element.url" class="btn btn-primary btn-sm" target="_blank">
                        <i class="fa fa-external-link-alt"></i>Abrir
                    </a>
                </td>
                <td class="<?php echo $cl_col['title'] ?>">
                    <p v-html="element.titulo"></p>
                    <p>
                        <span class="etiqueta nivel w1">{{ element.nivel }}</span>
                        <span class="etiqueta_a w3" v-bind:class="`etiqueta_a` + element.area_id">
                            {{ element.area_id | area_name }}
                        </span>
                    </p>
                </td>

                <td class="<?php echo $cl_col['tema'] ?>">
                    <a v-bind:href="`<?php echo base_url("temas/links/") ?>` + element.tema_id">
                        {{ element.nombre_tema }}
                    </a>
                </td>

                <td class="<?php echo $cl_col['info'] ?>">
                    <div>
                        <span class="text-muted">Palabras clave:</span>
                        <span>{{ element.palabras_clave }}</span>
                    </div>
                </td>
                
                <td>
                    <button class="btn btn-light btn-sm w27p" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>