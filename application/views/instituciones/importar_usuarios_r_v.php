<h3>Usuarios importados</h3>

<table class="table table-hover bg-blanco sep2">
    <thead>
        <th class="warning" width="35px">ID</th>
        <th>Usuario</th>
        <th>Username</th>
        <th>Rol</th>
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
                <?= $this->Item_model->nombre(6, $row_usuario->rol_id) ?>
            </td>
        </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>