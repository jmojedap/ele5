<?php
    $cl_col['selector'] = '';
    if ( $this->session->userdata('role') > 2 ) { $cl_col['selector'] = 'd-none'; }
?>

<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px"  class="<?php echo $cl_col['selector'] ?>">
                <input type="checkbox"  @click="select_all" v-model="all_selected">
            </th>
            <th width="80px"></th>
            <th>Link</th>
            <th>Tema</th>
            <th>Detalles</th>
            <th width="80px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td class="<?php echo $cl_col['selector'] ?>">
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td>
                    <a v-bind:href="element.url" class="btn btn-primary btn-sm" target="_blank">
                        <i class="fa fa-external-link-alt"></i>Abrir
                    </a>
                </td>
                <td>
                    <p v-html="element.titulo"></p>
                    
                </td>

                <td>
                    <a v-bind:href="`<?php echo base_url("temas/links/") ?>` + element.tema_id">
                        {{ element.nombre_tema }}
                    </a>
                </td>

                <td>
                    <dl class="row">
                        <dt class="col-md-3 text-right"></dt>
                        <dd class="col-md-9">
                            <span class="etiqueta nivel w1">{{ element.nivel }}</span>
                            <span class="etiqueta_a w3" v-bind:class="`etiqueta_a` + element.area_id">
                                {{ element.area_id | area_name }}
                            </span>
                        </dd>

                        <dt class="col-md-3 text-right">Palabras clave</dt>
                        <dd class="col-md-9">{{ element.palabras_clave }}</dd>

                        <dt class="col-md-3 text-right">Componente</dt>
                        <dd class="col-md-9">{{ element.componente_id | componente_name }}</dd>
                    </dl>
                </td>
                
                
                <td>
                    <button class="btn btn-info btn-sm btn-sm-square" data-toggle="modal" data-target="#modal_schedule" @click="set_current(key)" title="Programar link">
                        <i class="far fa-calendar-alt"></i>
                    </button>
                    <button class="btn btn-light btn-sm btn-sm-square" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>