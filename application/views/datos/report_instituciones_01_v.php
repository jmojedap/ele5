<table class="table table-hover bg-blanco">
    <thead>
        <th width="45px;" class="warning">ID</th>
        <th>Nombre institucion</th>
        <th>Estudiantes</th>
        <th>Login</th>
        <th>Promedio</th>
        <th>Pagaron</th>
        <th>% Pagaron</th>
    </thead>
    <tbody>
        <?php foreach ($instituciones->result() as $row_institucion) : ?>
            <?php
                $cant_login = $row_institucion->cant_login;
                $cant_estudiantes = $this->Institucion_model->cant_estudiantes($row_institucion->id);
                $cant_pagaron = $this->Institucion_model->cant_estudiantes($row_institucion->id, 'pago = 1');
    
                $porcentaje_pagaron = 0; 
                if ( $cant_estudiantes > 0 ) { $porcentaje_pagaron = 100 * $cant_pagaron / $cant_estudiantes; }
                
                $promedio_login = 0;
                if ( $cant_login > 0 ) { $promedio_login = $cant_login / $cant_estudiantes; } 
            ?>
            <tr>
                <td class="warning"><?= $row_institucion->id ?></td>
                <td><?= $row_institucion->nombre_institucion ?></td>
                <td><?= $cant_estudiantes ?></td>
                <td><?= number_format($row_institucion->cant_login, 0, ',', '.') ?></td>
                <td><?= number_format($promedio_login, 1, ',', '.') ?></td>
                <td>
                    <span class="resaltar"><?= $cant_pagaron ?></span>
                </td>
                <td>
                    <?= number_format($porcentaje_pagaron, 0) ?>%
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>