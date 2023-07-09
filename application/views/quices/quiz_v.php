<div class="">
    <p>
        <span class="etiqueta nivel w1"><?= $row_tema->nivel ?></span>
        <?= $this->App_model->etiqueta_area($row_tema->area_id) ?>
        <span class="etiqueta informacion w2"><?php echo $this->Item_model->nombre(9, $row->tipo_quiz_id) ?></span>
        <span class="suave"> | </span>
        
        <span class="suave">Tema:</span>
        <span class="resaltar"><?= anchor("admin/temas/quices/{$row->tema_id}", $row_tema->nombre_tema, 'class="" title=""') ?></span>
        <span class="suave"> | </span>

        <span class="suave">Código:</span>
        <span class="resaltar"> <?= $row->cod_quiz ?></span>
        <span class="suave"> | </span>
        
        <span class="suave">Editado por:</span>
        <span class="resaltar"> <?= $this->App_model->nombre_usuario($row->usuario_id, 2) ?></span>
        <span class="suave"> | </span>

        <span class="suave">Editado:</span>
        <span class="resaltar"> <?= $this->Pcrn->fecha_formato($row->editado, 'M-d') ?></span>
        <span class="suave"> | </span>
        
        <span class="suave">Hace:</span>
        <span class="resaltar"> <?= $this->Pcrn->tiempo_hace($row->editado) ?></span>
        <span class="suave"> | </span>
    </p>
    <p><?= $row->descripcion ?></p>
</div>