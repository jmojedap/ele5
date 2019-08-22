<div class="mb-1">
    <?php echo $this->App_model->etiqueta_area($row->area_id) ?>
    <span class="etiqueta nivel w1"><?= $row->nivel ?></span>
    <span class="text-muted"> | Código: </span>
    <span class="text-danger"><?= $row->cod_tema ?></span>
</div>