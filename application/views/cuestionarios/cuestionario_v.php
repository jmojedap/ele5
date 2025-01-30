<p>
    <span class="etiqueta nivel w1"><?= $row->nivel ?></span>
    <span class="resaltar"><?= $this->App_model->etiqueta_area($row->area_id) ?></span> &middot;

    <span class="text-muted">Preguntas: </span> 
    <span class="resaltar"><?= $row->num_preguntas ?></span>
    <span class="text-muted"> &middot; </span>

    <span class="text-muted">Tipo:</span> 
    <span class="resaltar"><?= $this->Item_model->nombre(15, $row->tipo_id) ?></span> &middot;
    <span class="text-muted">Creado por:</span> 
    <span class="resaltar"><?= $this->App_model->nombre_usuario($row->creado_usuario_id, 2) ?></span> &middot;

    <?php if ( ! is_null($row->institucion_id) ) : ?>                
        <span class="text-muted">Institución:</span> 
        <span class="resaltar"><?= $this->App_model->nombre_institucion($row->institucion_id) ?></span> &middot;
    <?php endif ?>

    <?php if ( $this->session->userdata('rol_id') == 0 ) { ?>
        <span class="text-muted">Key:</span> 
        <span class="resaltar"><?= $row->clave ?></span> &middot;
    <?php } ?>

</p>