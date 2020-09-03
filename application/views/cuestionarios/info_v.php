<div class="center_box_750">
    <table class="table bg-white">
        <tbody>
            <tr>
                <td>Nombre cuestionario</td>
                <td><?= $row->nombre_cuestionario ?></td>
            </tr>
            <tr>
                <td>Descripción</td>
                <td><?= $row->descripcion ?></td>
            </tr>
            <tr>
                <td>Tiempo asignado (minutos)</td>
                <td><?= $row->tiempo_minutos ?></td>
            </tr>
            <tr>
                <td>Institución</td>
                <td>
                    <a href="<?= base_url("instituciones/grupos/{$row->institucion_id}") ?>">
                        <?= $this->App_model->nombre_institucion($row->institucion_id); ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Preguntas</td>
                <td><?= $row->num_preguntas ?></td>
            </tr>
            <tr>
                <td>Nivel - Área</td>
                <td>
                    <span class="etiqueta nivel w1"><?= $row->nivel ?></span>
                    <span class="resaltar"><?= $this->App_model->etiqueta_area($row->area_id) ?></span>
                </td>
            </tr>
            <tr>
                <td>Actualizado por</td>
                <td>
                    <a href="<?= base_url("usuarios/actividad/{$row->editado_usuario_id}") ?>">    
                        <?= $this->App_model->nombre_usuario($row->editado_usuario_id, 'nau'); ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Actualizado en</td>
                <td>
                    <?= $this->pml->date_format($row->editado, 'Y-M-d'); ?> &middot;
                    <?= $this->pml->ago($row->editado); ?>
                </td>
            </tr>
            <tr>
                <td>Creado por</td>
                <td>
                    <a href="<?= base_url("usuarios/actividad/{$row->creado_usuario_id}") ?>">    
                        <?= $this->App_model->nombre_usuario($row->creado_usuario_id, 'nau'); ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Creado en</td>
                <td>
                    <?= $this->pml->date_format($row->creado, 'Y-M-d'); ?> &middot;
                    <?= $this->pml->ago($row->creado); ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>