<table class="table table-default bg-blanco">
    <thead>
        <th class="<?= $clases_col['usuario'] ?>">Usuario</th>
        <th class="<?= $clases_col['rol'] ?>">Rol</th>
    </thead>
    
    <tbody>
        <?php foreach($usuarios->result() as $row_usuario) : ?>
        <tr>
            <td class="<?= $clases_col['nombre_usuario'] ?>">
                <?= anchor("usuarios/actividad/{$row_usuario->id}", $row_usuario->nombre . ' ' . $row_usuario->apellidos) ?>
            </td>
            <td class="<?= $clases_col['rol'] ?>">
                <?= $this->Item_model->nombre(58, $row_usuario->rol_id); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        
    </tbody>
</table>