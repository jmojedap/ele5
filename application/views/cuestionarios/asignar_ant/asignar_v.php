<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/vue'); ?>
<?php $this->load->view('assets/toastr'); ?>

<?php
    //Selección de instituciones
        $opciones_institucion = $this->App_model->opciones_institucion();
?>

<?php $this->load->view('cuestionarios/asignar/docready_v'); ?>

<div id="app_asignar">
    <div class="row">
        <div class="col col-md-5">
            <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
                <div class="sep1">
                    <?php echo form_dropdown('institucion_id', $opciones_institucion, '0' . $institucion_id, 'id="campo-institucion_id" class="form-control chosen-select"') ?>
                </div>
            <?php } ?>

            <table class="table bg-blanco">
                <thead>
                    <th width="30%">Grupo</th>
                    <th width="25px">Minutos</th>
                    <th>Lapso</th>
                    <th width="85px"></th>
                </thead>

                <tbody>
                        <tr v-for="(grupo, key) in lista" v-bind:class="{'info':grupo_id == grupo.grupo_id}">
                            <td>
                                <button class="w3 btn btn-default" v-on:click="elemento_actual(key)" v-bind:class="{'btn-primary':grupo_id == grupo.grupo_id}">
                                    {{ grupo.nombre_grupo }}
                                </button>
                            </td>
                            <td>{{ grupo.tiempo_minutos }}</td>
                            <td>{{ grupo.fecha_inicio }} a {{ grupo.fecha_fin }}</td>
                            <td>
                                <button class="btn btn-default btn-sm" type="button" data-toggle="modal" data-target="#modal_formulario" v-on:click="cargar_formulario(key)">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal_eliminar" v-on:click="elemento_actual(key)" type='button'>
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                </tbody>
            </table>
            
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal_formulario">
                <i class="fa fa-plus"></i>
                Agregar grupo
            </button>

        </div>
        <div class="col col-md-7">
            <div id="lista_estudiantes">
                <div class="alert alert-info">
                    <i class="fa fa-info"></i>
                    Sin datos
                </div>
            </div>
        </div>
    </div>
    
    <?php $this->load->view('cuestionarios/asignar/formulario_v'); ?>
    
    <?php $this->load->view('comunes/modal_eliminar_simple'); ?>
</div>

<?php $this->load->view('cuestionarios/asignar/vue_v'); ?>
