<?php
    $i = $mensajes->num_rows(); //Contador de mensajes, orden inverso
?>

<table class="table table-default bg-blanco">
    <thead>
        <th class="<?= $clases_col['num_mensaje'] ?>" width="50px">No.</th>
        <th class="<?= $clases_col['usuario'] ?>">Usuario</th>
        <th class="<?= $clases_col['texto_mensaje'] ?>">Mensaje</th>
        <th class="<?= $clases_col['hace'] ?>">Hace</th>
    </thead>
    
    <tbody>
        <?php foreach($mensajes->result() as $row_mensaje) : ?>
        <?php
            $clase_fila = '';
            if ( $row_mensaje->usuario_id == $this->session->userdata('usuario_id') ) { $clase_fila = 'info'; }
        ?>
        
        <tr class="<?= $clase_fila ?>">
            <td class="<?= $clases_col['num_mensaje'] ?>">
                <?= $i ?>
            </td>
            <td class="<?= $clases_col['nombre_elemento'] ?>">
                <?= anchor("usuarios/actividad/{$row_mensaje->usuario_id}", $this->App_model->nombre_usuario($row_mensaje->usuario_id, 2), 'class="" title=""') ?>
            </td>
            <td class="<?= $clases_col['texto_mensaje'] ?>">
                <?= $row_mensaje->texto_mensaje ?>
                <?php if ( strlen($row_mensaje->url) ) { ?>
                    <br/>
                    <?= anchor($this->Pcrn->preparar_url($row_mensaje->url), $this->Pcrn->texto_url($row_mensaje->url), 'target="_blank"') ?>
                <?php } ?>
            </td>
            <td class="<?= $clases_col['hace'] ?>">
                <?= $this->Pcrn->tiempo_hace($row_mensaje->enviado); ?>
            </td>
        </tr>
        
        <?php $i -= 1; ?>
        <?php endforeach; ?>
        
    </tbody>
</table>