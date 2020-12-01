<div class="text-center" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x"></i>
</div>
<div class="table-responsive" v-show="!loading">
    <table class="table bg-white">
        <thead>
            <th width="46px">
                <input type="checkbox" id="checkbox_all_selected" @click="select_all" v-model="all_selected">
            </th>
            <th width="40px"></th>
            <th>Pregunta</th>
            <th>Detalles</th>
            <th width="150px">Dificultad</th>
            <th width="50px"></th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url("preguntas/index/") ?>` + element.id" class="btn btn-primary btn-sm">
                        Abrir
                    </a>
                </td>
                <td >
                    <p>
                        <span class="etiqueta nivel w1">{{ element.nivel }}</span>
                        <span class="etiqueta_a" v-bind:class="`etiqueta_a` + element.area_id">
                            {{ element.area_id | area_name }}
                        </span>
                    </p>
                    <p v-html="element.texto_pregunta"></p>
                    <a v-bind:href="element.url_imagen_pregunta" data-lightbox="image-1" data-title="Imagen asociada" v-if="element.archivo_imagen" class="btn btn-light">
                        <i class="far fa-image"></i>
                        Imagen asociada
                    </a>

                    <div v-if="element.version_id > 0">
                        <br>
                        <a v-bind:href="`<?php echo base_url("preguntas/version/") ?>` + element.id" class="btn btn-warning btn-sm" target="_blank" title="Tiene versión con cambios propuestos">
                            <i class="fa fa-exclamation-triangle"></i> Versión
                        </a>
                    </div>

                </td>

                <td>
                    <div>
                        <span class="text-muted">Palabras clave:</span>
                        <span>{{ element.palabras_clave }}</span>
                    </div>
                </td>

                <td>
                    <div class="progress" v-if="element.qty_answers > 0">
                        <div class="progress-bar" v-bind:class="element.difficulty | difficulty_class" role="progressbar" v-bind:style="`width: ` + element.difficulty + `%`" v-bind:aria-valuenow="element.difficulty" aria-valuemin="0" aria-valuemax="100">
                            {{ element.difficulty_level | difficulty_name }}
                        </div>
                    </div>
                </td>
                
                <td>
                    <button class="btn btn-warning btn-sm w27p" @click="add_to_selectorp_unique(element.id)" title="Agregar a lista para nuevo cuestionario">
                        <i class="fa fa-plus"></i>
                    </button>
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