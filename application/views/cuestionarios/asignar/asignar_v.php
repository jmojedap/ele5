<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>
<?php $this->load->view('assets/vue'); ?>
<?php $this->load->view('assets/toastr'); ?>

<?php $this->load->view('cuestionarios/asignar/jquery_v'); ?>

<div id="app_asignar">
    <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
        <div class="sep1">
            <?= form_dropdown('institucion_id', $opciones_institucion, $busqueda['i'], 'id="campo-institucion_id" class="form-control chosen-select" title="Filtrar por institución"'); ?>
        </div>
    <?php } ?>
    <div class="sep1">
        <?php echo form_dropdown('nivel', $opciones_nivel, $busqueda['n'], 'id="campo-nivel" class="form-control" title="Grupos por nivel"'); ?>
    </div>

    <div class="row">
        <div class="col col-md-4">
            <table class="table table-default bg-blanco">
                <thead>
                    <th>Grupo</th>
                </thead>
                <tbody>
                    <tr v-for="(grupo, key) in grupos">
                        <td>
                            <button class="w3 btn btn-default" v-on:click="grupo_actual(key)" v-bind:class="{'btn-primary':grupo_id == grupo.grupo_id}">
                                {{ grupo.nombre_grupo }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col col-md-8">
            <div id="lista_estudiantes">
                <div class="alert alert-info">
                    <i class="fa fa-info"></i>
                    Sin datos
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('cuestionarios/asignar/vue_v'); ?>



