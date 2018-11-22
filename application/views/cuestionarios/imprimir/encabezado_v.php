Nivel: <b><?= $row->nivel ?></b> |
Área:  <b><?= $this->Item_model->nombre_id($row->area_id) ?></b> |
Preguntas: <b><?= $row->num_preguntas ?></b> |
Creado por: <b><?= $this->App_model->nombre_usuario($row->creado_usuario_id, 2); ?></b> |

<?php if ( ! is_null($row->institucion_id) ) : ?>                
    Institución:</span> 
    <b><?= $this->App_model->nombre_institucion($row->institucion_id) ?></b> |
<?php endif ?>

Tipo:
<b><?php echo $this->Item_model->nombre(15, $row->tipo_id) ?></b>