<?php
    $cant_login = 0;
    $cant_estudiantes = $this->Institucion_model->cant_estudiantes($row->id);
    $cant_pagaron = $this->Institucion_model->cant_estudiantes($row->id, 'pago = 1');

    $cant_grupos = $this->Db_model->num_rows('grupo', "institucion_id = {$row->id}");

    $cant_usuarios_institucionales = $this->Db_model->num_rows('usuario', "institucion_id = {$row->id} AND rol_id <>6");
    
    $porcentaje_pagaron = 0; 
    if ( $cant_estudiantes > 0 ) { $porcentaje_pagaron = 100 * $cant_pagaron / $cant_estudiantes; }

    $promedio_login = 0;

    $clase_cant_estudiantes = '';
?>

<div class="center_box_750">
    <?php if ( $this->session->userdata('logged') && $this->session->userdata('role') <= 1 ) : ?>
        <div class="mb-2 text-right">
            <a href="<?= URL_APP . "instituciones/editar/edit/{$row->id}" ?>" class="btn btn-sm btn-light mr-1">
                Editar
            </a>
            <a href="<?= URL_APP . "instituciones/eliminar_pre/{$row->id}" ?>" class="btn btn-sm btn-light">
                Eliminar...
            </a>
        </div>
    <?php endif; ?>
    <table class="table bg-white">
        <tbody>
            <tr>
                <td>Código</td>
                <td><?= $row->cod ?></td>
            </tr>
            <tr>
                <td>Nombre</td>
                <td><?= $row->nombre_institucion ?></td>
            </tr>
            <tr>
                <td>Ciudad</td>
                <td><?= $row->lugar_nombre ?></td>
            </tr>
            <tr>
                <td>Cantidad grupos</td>
                <td>
                    <?= $cant_grupos ?>
                </td>
            </tr>
            <tr>
                <td>Cantidad profesores</td>
                <td>
                    <?= $cant_usuarios_institucionales ?>
                </td>
            </tr>
            <tr>
                <td>Cantidad estudiantes</td>
                <td>
                    <?= $cant_estudiantes ?>
                </td>
            </tr>
            <tr>
                <td>Estudiantes pagaron</td>
                <td>
                    <?= $cant_pagaron ?> (<?= number_format($porcentaje_pagaron,2) ?>%)
                </td>
            </tr>
            <tr>
                <td>Dirección</td>
                <td><?= $row->direccion ?></td>
            </tr>
            <tr>
                <td>Teléfono</td>
                <td><?= $row->telefono ?></td>
            </tr>
            <tr>
                <td>Correo electrónico</td>
                <td><?= $row->email ?></td>
            </tr>
            <tr>
                <td>Ejecutivo asignado</td>
                <td>
                    <a href="<?= URL_APP . "usuarios/index/{$row->ejecutivo_id}" ?>">
                        <?= $this->App_model->nombre_usuario($row->ejecutivo_id,2) ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Notas</td>
                <td><?= $row->notas ?></td>
            </tr>
        </tbody>
    </table>
</div>