<div id="cuestionariosApp">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <table class="table bg-white">
                    <thead>
                        <th></th>
                        <th>Asignados</th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="(cuestionario, key) in asignados">
                            <td width="10px">
                                {{ parseInt(cuestionario.orden) + 1 }}
                            </td>
                            <td>
                                <a v-bind:href="`<?= URL_APP . "cuestionarios/info/" ?>` + cuestionario.cuestionario_id">
                                    <span class="text-muted">{{ cuestionario.cuestionario_id }} &middot;</span>
                                    {{ cuestionario.nombre_cuestionario }}
                                </a>
                            </td>
                            <td>
                                <button class="a4"
                                    v-on:click="updatePosition(cuestionario.meta_id, parseInt(cuestionario.orden) - 1)" v-show="cuestionario.orden > 0">
                                    <i class="fa fa-arrow-up"></i>
                                </button>
                                <button class="a4"
                                    v-on:click="updatePosition(cuestionario.meta_id, parseInt(cuestionario.orden) + 1)"
                                    v-show="cuestionario.orden < (asignados.length-1)">
                                    <i class="fa fa-arrow-down"></i>
                                </button>
                            </td>
                            <td width="10px">
                                <button class="a4" v-on:click="removeCuestionario(cuestionario)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getCuestionarios">
                    <fieldset v-bind:disabled="loading">
                        <input type="text" name="q" v-model="filters.q" class="form-control" title="Buscar cuestionario" placeholder="Buscar cuestionario">
                    <fieldset>
                </form>

                <table class="table bg-white mt-2 table-sm">
                    <thead>
                        <th width="10px"></th>
                        <th width="10px">ID</th>
                        <th>Nombre ({{ qtyResults }} resultados)</th>
                    </thead>
                    <tbody>
                        <tr v-for="(cuestionario, key) in cuestionarios">
                            <td>
                                <button class="btn btn-light btn-sm" v-on:click="addCuestionario(cuestionario.id)">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </td>
                            <td>{{ cuestionario.id }}</td>
                            <td>{{ cuestionario.nombre_cuestionario }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/flipbooks/unidades/cuestionarios/vue_v') ?>