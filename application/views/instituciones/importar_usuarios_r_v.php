<h3 class="my-2">Usuarios importados</h3>

<table class="table table-hover bg-white">
    <thead>
        <th class="warning" width="35px">ID</th>
        <th>Usuario</th>
        <th>Username</th>
        <th>Rol</th>
    </thead>
    
    <tbody>
        <?php foreach($importados as $usuario_id) : ?>
            <?php
                $rUser = $this->Db_model->row_id('usuario', $usuario_id);
            ?>
        <tr>
            <td class="warning">
                <?= $rUser->id ?>
            </td>
            <td>
                <?= anchor("usuarios/actividad/{$rUser->id}", "{$rUser->nombre} {$rUser->apellidos}") ?>
            </td>
            <td>
                <?= $rUser->username ?>
            </td>
            <td>
                <?= $this->Item_model->nombre(6, $rUser->rol_id) ?>
            </td>
        </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>