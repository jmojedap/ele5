<div class="">
    <p>
        <span class="etiqueta nivel w1"><?= $row->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row->area_id) ?>
        <span class="etiqueta informacion w2"><?php echo $this->Item_model->nombre(9, $row->tipo_quiz_id) ?></span>
        &middot;
        
        <?php if ( $row->tema_id > 0 ) : ?>
            <span class="text-muted">Tema:</span>
            <span class="text-primary"><?= anchor("admin/temas/quices/{$row->tema_id}", $row_tema->nombre_tema, 'class="" title=""') ?></span>
            &middot;
        <?php endif; ?>

        <span class="text-muted">Código:</span>
        <span class="text-primary"> <?= $row->cod_quiz ?></span>
        &middot;
        
        <span class="text-muted">Editado por:</span>
        <span class="text-primary"> <?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?></span>
        &middot;

        <span class="text-muted">Editado:</span>
        <span class="text-primary"> <?= $this->Pcrn->fecha_formato($row->editado, 'M-d') ?></span>
        &middot;
        
        <span class="text-muted">Hace:</span>
        <span class="text-primary"> <?= $this->Pcrn->tiempo_hace($row->editado) ?></span>
        &middot;
        <a class="btn btn-sm btn-primary" href="<?= base_url("quices/resolver/{$row->id}") ?>" target="_blank">Vista previa</a>
    </p>
    <p><?= $row->descripcion ?></p>
</div>