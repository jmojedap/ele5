<div class="module_content">
    <span class="suave">Flipbooks asignados a los estudiantes de</span>
    <h4><?= $this->App_model->nombre_usuario($this->session->userdata('usuario_id'), 2) ?></h4>
</div>

<hr/>

<div class="module_content">
    <?php foreach ($grupos->result() as $row_grupo) : ?>
        <?php
            $att_link = 'class="a2"';
            if ( $grupo_id == $row_grupo->grupo_id ){
                $att_link = 'class="a2 seleccionado"';
            }
        ?>
        <?= anchor("grupos/flipbooks/{$row_grupo->grupo_id}", $this->App_model->nombre_grupo($row_grupo->grupo_id), $att_link) ?>
    <?php endforeach; ?>
</div>