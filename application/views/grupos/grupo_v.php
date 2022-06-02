<p>
    <span class="text-muted"><i class="far fa-calendar" title="Año generación"></i></span>
    <span class="resaltar"><?= $row->anio_generacion ?></span>
    <span class="text-muted"> &middot; </span>

    <span class="text-muted"><i class="fa fa-university"></i></span>
    <span class="resaltar"> <?= $this->App_model->nombre_institucion($row->institucion_id, 1) ?></span>
    <span class="text-muted"> &middot; </span>

    <span class="text-muted">Estudiantes:</span>
    <span class="resaltar"> <?= $cant_estudiantes ?></span>
    
    <a href="<?= base_url("mensajes/nuevo_grupal/{$row->id}") ?>" class="btn btn-info btn-sm ml-3">
        <i class="far fa-comment"></i>
        Mensaje grupal
    </a>
    <a href="<?= base_url("grupos/exportar_estudiantes/{$row->id}") ?>" class="btn btn-success btn-sm">
        <i class="fa fa-download"></i>
        Listado
    </a>
</p>