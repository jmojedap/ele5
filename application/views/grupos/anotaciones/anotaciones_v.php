<?php
    foreach( $flipbooks->result() as $row_flipbook ) {
        $opciones_flipbook['0' . $row_flipbook->flipbook_id] = $this->App_model->nombre_flipbook($row_flipbook->flipbook_id);
    }
?>

<div id="anotaciones_app">
    <div class="container">
        <div class="text-center" v-show="loading">
            <div class="spinner-border text-secondary" role="status">
            <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="mb-2">
            <a v-bind:href="link_export" class="btn btn-success w100p">
                <i class="fa fa-file-excel"></i>
                Exportar
            </a>
        </div>
        <div class="row mb-2">
            <div class="col-md-6 col-sm-12">
                <?= form_dropdown('flipbook_id', $opciones_flipbook, $flipbook_id, 'class="form-control" v-model="flipbook_id" v-on:change="update_flipbook"') ?>
            </div>
            <div class="col-md-6 col-sm-12">
                <select name="tema_id" id="" v-model="tema_id" class="form-control" v-on:change="get_list">
                    <option value="0"> >> Todos los temas</option>
                    <option v-for="(tema, tema_key) in temas" v-bind:value="`0` + tema.id">{{ tema.nombre_tema }}</option>
                </select>
            </div>
        </div>
        
        <div class="table-responsive">

            <table class="table bg-white">
                <thead>
                    <th style="min-width: 150px;"></th>
                    <th>Pregunta abierta</th>
                    <th>Anotación/Respuesta</th>
                    <th width="140px">Calificación</th>
                    <th width="120px"></th>
                </thead>
                <tbody>
                    <tr class="">
                        <td></td>
                        <td></td>
                        <td><strong>PROMEDIO</strong></td>
                        <td style="width: 150px;" class="d-flex justify-content-center">
                            <div v-for="star in stars">
                                <span v-show="!displayStar(avg_calificacion, star)">
                                    <i class="star far fa-star"></i>
                                </span>
                                <span v-show="displayStar(avg_calificacion, star)">
                                    <i class="star fas fa-star"></i>
                                </span>
                            </div>
                        </td>
                        <td class="text-center" v-bind:class="calificacion_color(avg_calificacion)">
                            {{ calificacion_name(avg_calificacion) }}
                            ({{ avg_calificacion }}%)
                        </td>
                    </tr>
                    <tr v-for="(anotacion, anotacion_key) in anotaciones">
                        <td>
                            <a v-bind:href="`<?php echo base_url("usuarios/anotaciones/") ?>` + anotacion.usuario_id + `/` + anotacion.flipbook_id">
                                {{ anotacion.nombre_estudiante }}
                            </a>
                            <br>
                            <span class="text-muted" v-bind:title="anotacion.editado">Hace {{ anotacion.editado | ago }}</span>
                        </td>
                        <td>
                            {{ pa_texto(anotacion.flipbook_id, anotacion.tema_id) }}
                        </td>
                        <td>
                            <span class="resaltar"> {{ anotacion.nombre_tema }}</span>
                            <p>{{ anotacion.anotacion }}</p>
                        </td>
                        <td>
                            <div v-bind:class="{'pointer': calificable == true }" class="d-flex justify-content-center">
                                <div v-for="star in stars">
                                    <span v-on:click="set_calificacion(anotacion_key, star * 20)" v-show="displayStar(anotacion.calificacion, star)">
                                        <i class="star fa-star fas"></i>
                                    </span>
                                    <span v-on:click="set_calificacion(anotacion_key, star * 20)" v-show="!displayStar(anotacion.calificacion, star)">
                                        <i class="star fa-star far"></i>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center" v-bind:class="calificacion_color(anotacion.calificacion)">
                            {{ calificacion_name(anotacion.calificacion) }}    
                        </td>
                    </tr>
                </tbody>
    
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('grupos/anotaciones/vue_v') ?>