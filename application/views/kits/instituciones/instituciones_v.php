<div id="kitsInstitucionesApp">
    <div class="row">
        <div class="col col-sm-4">
            <div>
                <form accept-charset="utf-8" method="POST" id="searchInstitucionForm"
                    @submit.prevent="searchInstituciones">
                    <fieldset v-bind:disabled="loading">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Buscar institución"
                                name="q" v-model="filters.q"
                                aria-label="Buscar institución" aria-describedby="button-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-light" type="submit">
                                    <i class="fa fa-search" v-show="!loading"></i>
                                    <i class="fa fa-spin fa-spinner" v-show="loading"></i>
                                </button>
                            </div>
                        </div>
                    <fieldset>
                </form>
            </div>

            <table class="table bg-white">
                <tbody>
                    <tr v-for="resultado in institucionesBuscadas" v-show="!inCurrentInstituciones(resultado.id)">
                        <td class="table-warning" width="40px">{{ resultado.id }}</td>
                        <td class="">
                            <a v-bind:href="`<?= URL_APP . "instituciones/grupos" ?>` + resultado.id" target="_blank">
                                {{ resultado.nombre_institucion }}
                            </a>
                        </td>
                        <td width="50px">
                            <button class="btn btn-light btn-sm" v-on:click="agregarInstitucion(resultado.id)" v-bind:disabled="loading">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col col-sm-8">
            <div class="mb-2">
                <button class="btn btn-light w120p" v-on:click="runProcess(0)" v-bind:disabled="loading || selected.length == 0">
                    Actualizar <span>{{ selected.length }}</span>
                </button>
                <button class="btn btn-primary" v-on:click="runProcess(1)" v-bind:disabled="loading || selected.length == 0"
                    title="Actualizar asignaciones del kit eliminando las asignaciones de los elementos que ya no están en el kit"
                    >
                    Actualizar y Depurar <span>{{ selected.length }}</span>
                </button>
            </div>
            <table class="table table-default bg-white">
                <thead>
                    <th width="40px">
                        <input type="checkbox" @click="selectAll" v-model="allSelected" v-bind:disabled="loading">
                    </th>
                    <th width="40px" class="table-warning">ID</th>
                    <th>Institución</th>
                    <th>Estado</th>
                    <th>Actualizado</th>
                    <th>Hace</th>
                    <th width="40px"></th>
                </thead>
                <tbody>
                    <tr v-for="(institucion,key) in instituciones"
                        v-bind:class="{'table-info': institucion.id == currInstitucion.id, 'table-info': institucion.asignacion_id == updatingAsignacionId }"
                        >
                        <td>
                            <input type="checkbox" v-bind:id="`check_` + institucion.asignacion_id"
                                v-model="selected" v-bind:value="institucion.asignacion_id"
                                v-bind:disabled="loading"
                            >
                        </td>
                        <td class="table-warning">{{ institucion.id }}</td>
                        <td>
                            <a v-bind:href="`<?= URL_APP . "instituciones/flipbooks/" ?>` + institucion.id" class="clase" target="_blank">
                                {{ institucion.nombre_institucion }}
                            </a>
                        </td>
                        <td v-bind:class="{'table-danger': kit.editado > institucion.editado }">
                            <div v-show="updatingAsignacionId == 0">
                                <span v-show="kit.editado < institucion.editado">Asignado</span>
                                <span v-show="kit.editado > institucion.editado">Desactualizado</span>
                            </div>
                            <div class="text-muted" role="status" v-show="institucion.asignacion_id == updatingAsignacionId">
                                <i class="fa fa-spin fa-spinner"></i> Actualizando
                            </div>
                        </td>
                        <td>{{ dateFormat(institucion.editado) }}</td>
                        <td>{{ ago(institucion.editado) }}</td>
                        <td>
                            <button class="btn btn-light btn-sm" title="Eliminar institución del kit" v-on:click="setCurrent(key)"
                                data-toggle="modal" data-target="#delete_modal" v-bind:disabled="loading"
                                    >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<?php $this->load->view('kits/instituciones/vue_v') ?>