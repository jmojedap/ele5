<?php
    //Selección de grupos
    $str_grupos = '0';
    $arr_grupos = $this->session->userdata('arr_grupos');
    if ( count($arr_grupos) > 0 ) { $str_grupos = implode(',', $arr_grupos); }
    $condicion_grupos = 'grupo.id IN (' . $str_grupos . ')';
    $opciones_grupo = $this->App_model->opciones_grupo($condicion_grupos);

    $default_date = date('Y-m-d');

    $options_hour = array();
    for ($i=0; $i < 24; $i++) { 
        $value = substr('0' . $i,-2) . ' am';
        if ( $i > 12 ) { $value = substr('0' . ($i-12),-2) . ' pm'; }
        $options_hour[$i] = $value;
    }

    $options_minute = array();
    for ($i=0; $i < 60; $i += 5) { 
        $options_minute[$i] = substr('0' . $i,-2);
    }
?>

<div class="modal" tabindex="-1" role="dialog" id="sesionv_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Programar Sesión Virtual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form accept-charset="utf-8" method="POST" id="sesionv_form">
                    <input class="d-none" type="text" name="referente_2_id" id="sesionv-referente_2_id" value="10">
                    <div class="row">
                        <div class="col-md-3">
                            <p>Sesión virtual en:</p>
                        </div>
                        <div class="col-md-9">
                            <img id="sesionv_img" src="<?php echo URL_IMG ?>medios_videollamadas/zoom.png" alt="Logo Medio Vídeo Llamada" class="rounded mb-2 logo_mvl">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="url" class="col-md-3 col-form-label">Fecha y Hora</label>
                        <div class="col-md-3">
                            <input type="text" id="sesionv-fecha_inicio" name="fecha_inicio" required value="<?php echo $default_date ?>"
                                class="form-control bs_datepicker">
                        </div>
                        <div class="col-md-3">
                            <?php echo form_dropdown('hour', $options_hour, '9', 'class="form-control" title="Hora de inicio"') ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo form_dropdown('minute', $options_minute, '0', 'class="form-control" title="Minuto de inicio"') ?>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="url">URL sesión virtual</label>
                        <input type="url" id="sesionv-url" name="url" required class="form-control" title="Link a la sesión virtual" placeholder="URL">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea
                            id="sesionv-descripcion"
                            name="descripcion"
                            required
                            rows="2"
                            class="form-control"
                            placeholder="Descripción"
                            title="Descripción"
                            ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="grupo_id">Grupo</label>
                        <?= form_dropdown('grupo_id', $opciones_grupo, '', 'id="sesionv-grupo_id" class="form-control" required title="Elija el grupo al cual le asigna el link"') ?>
                    </div>

                    <div class="float-right">
                        <button type="button" class="btn btn-secondary w120p" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary w120p" type="submit">
                            Guardar
                        </button>
                    </div>

                    <button class="btn btn-warning eliminar_link" type="button">
                        <i class="fa fa-trash"></i>
                    </button>

                    <a id="sesionv_link_evento_actual" href="#" target="_blank" class="btn btn-info" title="Abrir el link">
                        <i class="fa fa-external-link-alt"></i> Abrir
                    </a>
                </form>
            </div>

        </div>
    </div>
</div>