<p>
    <span class="resaltar"> <?= $this->App_model->etiqueta_area($row->area_id) ?></span>
    <span class="resaltar"><span class="etiqueta nivel w1"><?= $row->nivel ?></span></span> &middot;
    <span class="suave">Código:</span>
    <span class="resaltar"> <?= $row->cod_pregunta ?></span> &middot;
    <span class="suave">Tema:</span>
    <span class="resaltar"> <?= anchor("admin/temas/preguntas/{$row->tema_id}", $this->App_model->nombre_tema($row->tema_id)) ?></span> &middot;
    <span class="suave">Componente:</span>
    <span class="resaltar"> <?= $this->App_model->nombre_item($row->componente_id) ?></span> &middot;
    <span class="suave">Competencia:</span>
    <span class="resaltar"> <?= $this->App_model->nombre_item($row->competencia_id) ?></span> &middot;
    <span class="suave">Tipo:</span>
    <span class="resaltar"> <?= $this->Item_model->name(156, $row->tipo_pregunta_id) ?></span> &middot;
    <span class="suave">Proceso de pensamiento:</span>
    <span class="resaltar"> <?= $row->proceso_pensamiento ?></span> &middot;
</p>
<p>
    <?= $row->notas ?>
</p>