<h3>Usuarios importados</h3>

<table class="table table-hover bg-blanco sep2">
    <thead>
        <th class="warning" width="35px">ID</th>
        <th>Usuario</th>
        <th>Username</th>
        <th>Grupo</th>
    </thead>
    
    <tbody>
        <?php foreach($importados as $usuario_id) : ?>
            <?php
                $row_usuario = $this->Pcrn->registro_id('usuario', $usuario_id);
            ?>
        <tr>
            <td class="warning">
                <?= $row_usuario->id ?>
            </td>
            <td>
                <?= anchor("usuarios/actividad/{$row_usuario->id}", "{$row_usuario->nombre} {$row_usuario->apellidos}") ?>
            </td>
            <td>
                <?= $row_usuario->username ?>
            </td>
            <td>
                <?= anchor("grupos/estudiantes/{$row_usuario->grupo_id}", $this->App_model->nombre_grupo($row_usuario->grupo_id), 'class="btn btn-primary" title=""') ?>
            </td>
        </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>