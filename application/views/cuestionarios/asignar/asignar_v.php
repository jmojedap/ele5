<div id="asignarCuestionarioApp">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <p>
                    En esta sección puede asignar o editar la asignación de los estudiantes de un grupo al cuestionario
                    <span class="resaltar"><?= $row->nombre_cuestionario ?></span>.
                    Si un estudiante ya ha sido agregado previamente al cuestionario no se asignará de nuevo pero se modificarán la fecha
                    inicial y final, y el tiempo para responder.
                    </p>

                    <hr/>

                    <div class="d-flex justify-content-around">
                        <p>
                            <span class="badge"
                                v-bind:class="{'badge-success': gruposSeleccionados.length > 0, 'badge-warning': gruposSeleccionados.length == 0 }">
                                {{ gruposSeleccionados.length }}
                            </span>
                            Grupos
                        </p>
                        <p>
                            <span class="badge"
                                v-bind:class="{'badge-success': estudiantesSeleccionados.length > 0, 'badge-warning': estudiantesSeleccionados.length == 0 }"
                                >
                                {{ estudiantesSeleccionados.length }}
                            </span>
                            Estudiantes
                        </p>
                    </div>
                    <form accept-charset="utf-8" method="POST" id="asignarForm" @submit.prevent="handleSubmit">
                        <fieldset v-bind:disabled="loading">
                            <input type="hidden" name="cuestionario_id" value="<?= $row->id ?>">
                            <input type="hidden" name="estudiantes_seleccionados" v-model="estudiantesSeleccionados">
                            
                            <div class="mb-3 row">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-success w-100" type="submit" v-bind:disabled="estudiantesSeleccionados.length==0">Asignar</button>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="tiempo_minutos" class="col-md-4 col-form-label text-right">Tiempo en minutos</label>
                                <div class="col-md-8">
                                    <input
                                        name="tiempo_minutos" type="number" class="form-control" min="10"
                                        required
                                        title="Tiempo en minutos" placeholder="Tiempo en minutos"
                                        v-model="fields.tiempo_minutos"
                                    >
                                    <small class="form-text text-muted">Tiempo para responder el cuestionario</small>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="fecha_inicio" class="col-md-4 col-form-label text-right">Responder desde</label>
                                <div class="col-md-8">
                                    <input
                                        name="fecha_inicio" type="date" class="form-control" required
                                        v-model="fields.fecha_inicio"
                                    >
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="fecha_fin" class="col-md-4 col-form-label text-right">Responder hasta</label>
                                <div class="col-md-8">
                                    <input
                                        name="fecha_fin" type="date" class="form-control" required
                                        v-model="fields.fecha_fin"
                                    >
                                </div>
                            </div>
                        <fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <?php if ( $this->session->userdata('srol') == 'interno' ) : ?>
                <div>
                    <div class="mb-3 row">
                        <label for="institucion_id" class="col-md-4 col-form-label text-end text-right">Institución</label>
                        <div class="col-md-8">
                            <select name="institucion_id" v-model="institucionId" class="form-select form-control" v-on:change="setInstitucion">
                                <option v-for="optionInstitucion in instituciones" v-bind:value="optionInstitucion.cod">{{ optionInstitucion.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <ul class="nav nav-pills justify-content-start mb-2">
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" aria-disabled="true">Grupo:</a>
                </li>
                <li class="nav-item" v-for="(grupo,key) in grupos">
                    
                    <a class="nav-link pointer" v-on:click="setCurrGrupo(key)" href="#"
                        v-bind:class="{'active': grupo.id == currGrupo.id }"
                    >
                        {{ grupo.nombre }}
                    </a>
                </li>
            </ul>
            <table class="table bg-white">
                <thead>
                    <th>
                        <input type="checkbox" v-for="(grupo,key) in grupos" @change="selectAll(key)"
                            v-model="grupo.selected" v-show="grupo.id == currGrupo.id"
                            >
                    </th>
                    <th>Estudiante</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="(estudiante, key) in estudiantes"
                        v-bind:class="{'table-info': estudiante.selected, 'table-warning': estudiante.assigned}"
                        v-show="estudiante.grupo_id == currGrupo.id"
                        >
                        <td width="10px">
                            <input type="checkbox"
                                v-bind:id="`check_grupo_` + estudiante.id" v-model="estudiante.selected"
                                v-bind:value="estudiante.id" v-bind:disabled="estudiante.assigned"
                                >
                        </td>
                        <td>{{ estudiante.display_name }}</td>
                        <td><span v-show="estudiante.assigned">Asignado</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('cuestionarios/asignar/vue_v') ?>