<div id="temasApp">
    <div class="row">
        <div class="col-md-4">
            <form accept-charset="utf-8" method="POST" id="search-temas-form" @submit.prevent="searchTemas">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3">
                        <input
                            name="q" type="text" class="form-control"
                            required
                            title="Buscar" placeholder="Buscar"
                            v-model="filters.q"
                        >
                    </div>
                <fieldset>
            </form>
            <table class="table bg-white table-sm">
                <thead>
                    <th>ID</th>
                    <th>Tema</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="(element, j) in results">
                        <td>{{ element.id }}</td>
                        <td>
                            <strong class="text-primary">
                                {{ element.cod_tema }}
                            </strong>
                            &middot;
                            {{ element.nombre_tema }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-light btn-sm" v-on:click="saveProgramaTema(element.id)" title="Agregar al programa">
                                <i class="fas fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <p>Temas en el programa: <strong class="text-primary">{{ temas.length }}</strong></p>
            <div class="btn-toolbar mb-2" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group me-2" role="group" aria-label="First group">
                    <button class="btn btn-link" disabled>
                        Unidad
                    </button>
                    <button type="button" class="btn btn-outline-primary" v-on:click="currentUnidad = -1">
                        Todas
                    </button>
                    <button type="button" class="btn btn-primary w2" v-for="unidad in unidades" :key="unidad"
                        v-bind:class="{'btn-primary': currentUnidad == unidad, 'btn-outline-primary': currentUnidad != unidad }"
                        v-on:click="currentUnidad = unidad"
                        >
                        {{ unidad }}
                    </button>
                
                </div>
            </div>
            <table class="table bg-white table-sm">
                <thead>
                    <th class="table-warning">ID</th>
                    <th>Unidad</th>
                    <th>Num</th>
                    <th>Código</th>
                    <th>Nombre tema</th>
                    <th width="220">Nivel - Área</th>
                    <th width="120">Botones</th>
                </thead>
                <tbody>
                    <tr v-for="(tema, key) in temas" v-bind:class="{'table-info': currentTema.id == tema.id }" v-show="tema.unidad == currentUnidad || currentUnidad == -1">
                        <td class="table-warning">{{ tema.id }}</td>
                        <td class="text-center">{{ tema.unidad }}</td>
                        <td class="text-center">{{ parseInt(tema.orden) + 1 }}</td>
                        <td>{{ tema.cod_tema }}</td>
                        <td>
                            <a v-bind:href="`<?= base_url('admin/temas/info/') ?>` + tema.id" class="">
                                {{ tema.nombre_tema }}
                            </a>
                        </td>
                        <td>
                            <span class="etiqueta nivel w2">{{ tema.nivel }}</span>
                            <span class="etiqueta" v-bind:class="`bg-area-` + tema.area_id">{{ areaName(tema.area_id) }}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-light" v-on:click="moverTema(tema.id,key-1)" v-bind:disabled="key == 0">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                            <button class="btn btn-sm btn-light" v-on:click="moverTema(tema.id,key+1)" v-bind:disabled="key == temas.length - 1">
                                <i class="fa fa-chevron-down"></i>
                            </button>
        
                            <button
                                v-on:click="setCurrent(key)" data-toggle="modal" data-target="#delete_modal"
                                class="btn btn-sm btn-warning" title="Quitar tema de este programa, no se elimina"
                                >
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php $this->load->view('common/bs4/modal_single_delete_v') ?>

</div>

<?php $this->load->view('admin/programas/temas/vue_v') ?>