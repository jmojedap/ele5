<p>
    <span class="resaltar"> <?php echo $this->App_model->etiqueta_area($row->area_id) ?></span>
    <span class="resaltar"><span class="etiqueta nivel w1"><?php echo $row->nivel ?></span></span> |
    <span class="suave">Código:</span>
    <span class="resaltar"> <?php echo $row->cod_pregunta ?></span> |
    <span class="suave">Tema:</span>
    <span class="resaltar"> <?php echo anchor("temas/preguntas/{$row->tema_id}", $this->App_model->nombre_tema($row->tema_id)) ?></span> |
    <span class="suave">Componente:</span>
    <span class="resaltar"> <?php echo $this->App_model->nombre_item($row->componente_id) ?></span> |
    <span class="suave">Competencia:</span>
    <span class="resaltar"> <?php echo $this->App_model->nombre_item($row->competencia_id) ?></span> |
    <span class="suave">Proceso de pensamiento:</span>
    <span class="resaltar"> <?php echo $row->proceso_pensamiento ?></span> |
</p>
<p>
    <?php echo $row->notas ?>
</p>