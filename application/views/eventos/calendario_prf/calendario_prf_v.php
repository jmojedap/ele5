<?php $this->load->view('assets/fullcalendar4'); ?>
<?php $this->load->view('assets/bootstrap_datepicker'); ?>

<?php
    //Selección de grupos
        $str_grupos = '0';
        $arr_grupos = $this->session->userdata('arr_grupos');
        if ( count($arr_grupos) > 0 ) { $str_grupos = implode(',', $arr_grupos); }
        $condicion_grupos = 'grupo.id IN (' . $str_grupos . ')';
        $opciones_grupo = $this->App_model->opciones_grupo($condicion_grupos);
        
    //
        
        
    //Get para link print
        $get_print = $this->Pcrn->get_str();
?>

<?php $this->load->view('eventos/calendario_prf/script_v') ?>

<div class="row">
    <div class="col-md-3">
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_grupos_v'); ?>
        </div>
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_areas_v'); ?>
        </div>
        <div class="mb-2">
            <?php $this->load->view('eventos/filtro_tipos_v'); ?>
        </div>
        
        <div class="mb-2">
            <a href="<?php echo base_url("eventos/imprimir_calendario/?{$get_print}") ?>" class="btn btn-info btn-block" id="boton_print" target="_blank">
                <i class="fa fa-print"></i> Imprimir
            </a>
        </div>
    </div>
    <div class="col-md-9">
        <div id='calendar'></div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="evento_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Programar enlace</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <form accept-charset="utf-8" method="POST" id="evento_form">    
                <p>Haga clic en la fecha donde programará el enlace</p>
                <div class="form-group">
                    <label for="url">Fecha</label>
                    <input
                        type="text"
                        id="field-fecha_inicio"
                        name="fecha_inicio"
                        required
                        class="form-control bs_datepicker"
                        placeholder="AAAA-MM-DD"
                        title="AAAA-MM-DD"
                        >
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input
                        type="url"
                        id="field-url"
                        name="url"
                        required
                        class="form-control"
                        placeholder="Escriba la URL"
                        title="Escriba la URL"
                        >
                </div>
                <div class="form-group">
                    <label for="grupo_id">Grupo</label>
                    <?= form_dropdown('grupo_id', $opciones_grupo, '', 'id="field-grupo_id" class="form-control" required title="Elija el grupo al cual le asigna el link"') ?>
                </div>

                <div class="float-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">
                        Guardar
                    </button>
                </div>
                
                <button id="eliminar_link" class="btn btn-warning" type="button">
                    <i class="fa fa-trash"></i> Eliminar
                </button>
                
                <a id="link_evento_actual" href="#" target="_blank" class="btn btn-info" title="Abrir el link">
                    <i class="fa fa-external-link-alt"></i> Abrir
                </a>
            </form>   
        </div>
    </div>
</div>
</div>