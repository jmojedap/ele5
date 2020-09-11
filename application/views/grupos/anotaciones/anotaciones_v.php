<?php
    foreach( $flipbooks->result() as $row_flipbook ) {
        $opciones_flipbook['0' . $row_flipbook->flipbook_id] = $this->App_model->nombre_flipbook($row_flipbook->flipbook_id);
    }
?>

<div id="anotaciones_app">
    <div class="container">
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
        <table class="table bg-white">
            <thead>
                <th style="min-width: 150px;"></th>
                <th>Anotación/Respuesta</th>
                <th width="120px">Calificación</th>
                <th width="120px"></th>
            </thead>
            <tbody>
                <tr class="">
                    <td></td>
                    <td><strong>PROMEDIO</strong></td>
                    <td>
                        <i class="star fa-star" v-bind:class="star_class(avg_calificacion, 1)"></i>
                        <i class="star fa-star" v-bind:class="star_class(avg_calificacion, 2)"></i>
                        <i class="star fa-star" v-bind:class="star_class(avg_calificacion, 3)"></i>
                        <i class="star fa-star" v-bind:class="star_class(avg_calificacion, 4)"></i>
                        <i class="star fa-star" v-bind:class="star_class(avg_calificacion, 5)"></i>
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
                        <span class="resaltar"> {{ anotacion.nombre_tema }}</span>
                        <p>{{ anotacion.anotacion }}</p>
                    </td>
                    <td>
                        <i class="pointer star fa-star" v-bind:class="star_class(anotacion.calificacion, 1)" v-on:click="set_calificacion(anotacion_key, 20)"></i>
                        <i class="pointer star fa-star" v-bind:class="star_class(anotacion.calificacion, 2)" v-on:click="set_calificacion(anotacion_key, 40)"></i>
                        <i class="pointer star fa-star" v-bind:class="star_class(anotacion.calificacion, 3)" v-on:click="set_calificacion(anotacion_key, 60)"></i>
                        <i class="pointer star fa-star" v-bind:class="star_class(anotacion.calificacion, 4)" v-on:click="set_calificacion(anotacion_key, 80)"></i>
                        <i class="pointer star fa-star" v-bind:class="star_class(anotacion.calificacion, 5)" v-on:click="set_calificacion(anotacion_key, 100)"></i>
                    </td>
                    <td class="text-center" v-bind:class="calificacion_color(anotacion.calificacion)">
                        {{ calificacion_name(anotacion.calificacion) }}    
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</div>
<?php $this->load->view('grupos/anotaciones/vue_v') ?>