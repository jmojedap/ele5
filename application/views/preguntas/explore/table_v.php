<?php
    $cl_col['title'] = '';
    $cl_col['open'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['level_area'] = 'd-none d-md-table-cell d-lg-table-cell';
    $cl_col['difficulty'] = 'd-none d-md-table-cell d-lg-table-cell';
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
            <th class="<?php echo $cl_col['open'] ?>" width="40px"></th>
            <th class="<?php echo $cl_col['title'] ?>">Texto Pregunta</th>
            <th class="<?php echo $cl_col['difficulty'] ?>" width="150px">Dificultad</th>
            <th class="<?php echo $cl_col['level_area'] ?>">Nivel Área</th>
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
                    <a v-bind:href="`<?php echo base_url("preguntas/index/") ?>` + element.id" class="btn btn-primary btn-sm">
                        Abrir
                    </a>
                </td>
                <td class="<?php echo $cl_col['title'] ?>">
                    <div v-html="element.texto_pregunta"></div>
                    <div v-if="element.version_id > 0">
                        <br>
                        <a v-bind:href="`<?php echo base_url("preguntas/version/") ?>` + element.id" class="btn btn-warning btn-sm" target="_blank" title="Tiene versión con cambios propuestos">
                            <i class="fa fa-exclamation-triangle"></i> Versión
                        </a>
                    </div>
                </td>

                <td class="<?php echo $cl_col['difficulty'] ?>">
                    <div class="progress">
                        <div class="progress-bar" v-bind:class="element.difficulty | difficulty_class" role="progressbar" v-bind:style="`width: ` + element.difficulty + `%`" v-bind:aria-valuenow="element.difficulty" aria-valuemin="0" aria-valuemax="100">
                            {{ element.difficulty | difficulty_name }}
                        </div>
                    </div>
                </td>

                <td class="<?php echo $cl_col['level_area'] ?>">
                    <span class="etiqueta nivel w1">{{ element.nivel }}</span>
                    <span class="etiqueta_a" v-bind:class="`etiqueta_a` + element.area_id">
                        {{ element.area_id | area_name }}
                    </span>
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