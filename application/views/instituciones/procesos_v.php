<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<table class="table table-hover bg-blanco" cellspacing="0">
    <thead>
        <th style="width: 100px;">Ejecutar</th>
        <th>Procesos</th>
        <th>Descripción</th>
    </thead>
    <tbody>
        <tr>
            <td><?= anchor("instituciones/actualizar_acumulador/{$row->id}", 'Ejecutar', 'class="btn btn-default"') ?></td>
            <td>Actualizar acumulador</td>
            <td>
                Actualiza el campo usuario_pregunta.acumulador, para representar las gráficas resultados de cuestionarios
                por componentes
            </td>
        </tr>
        
        <tr>
            <td><?= anchor("instituciones/desactivar_morosos/{$row->id}", 'Ejecutar', 'class="btn btn-default"') ?></td>
            <td>Desactivar estudiantes morosos</td>
            <td>
                Desactivar a los estudiantes que están marcados como "Pago: No". Se desactivan si la fecha actual es posterior
                a la fecha de vencimiento de la Institución: 
                <span class="resaltar">
                    <?= $this->Pcrn->fecha_formato($row->vencimiento_cartera, 'd-M') ?>
                </span>(<?= $this->Pcrn->tiempo_hace($row->vencimiento_cartera, 1) ?>).
            </td>
        </tr>
        
        <tr>
            <td><?= anchor("instituciones/activar_todos/{$row->id}", 'Ejecutar', 'class="btn btn-default"') ?></td>
            <td>Activar a todos los usuarios de la institución</td>
            <td>
                Se activa a todos los usuarios de una institución.
            </td>
        </tr>
        
        <tr>
            <td><?= $this->Pcrn->anchor_confirm("instituciones/eliminar_actividad/{$row->id}", 'Ejecutar', 'class="btn btn-default"', '¿Confirma la eliminación de los registros?') ?></td>
            <td>Eliminar actividad de Usuarios</td>
            <td>
                Se elimina la actividad de los administradores, directivos y profesores de la institución.
            </td>
        </tr>
        
        <tr>
            <td><?= $this->Pcrn->anchor_confirm("instituciones/eliminar_actividad/{$row->id}/estudiantes", 'Ejecutar', 'class="btn btn-default"', '¿Confirma la eliminación de los registros?') ?></td>
            <td>Eliminar actividad de Estudiantes</td>
            <td>
                Se elimina la actividad de los estudiantes de la institución.
            </td>
        </tr>

    </tbody>
</table>




