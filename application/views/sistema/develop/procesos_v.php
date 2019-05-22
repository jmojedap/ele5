<?php $this->load->view('sistema/develop/procesos_menu_v'); ?>
<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<table class="table table-hover bg-blanco" cellspacing="0">
    <thead>
        <th style="width: 100px;">Ejecutar</th>
        <th style="width: 20%">Procesos</th>
        <th>Descripción</th>
    </thead>
    <tbody>

        <?php foreach($procesos->result() as $row_proceso) : ?>
        <tr>
            <td><?= anchor($row_proceso->link_proceso, 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td><?= $row_proceso->nombre_proceso ?></td>
            <td><?= $row_proceso->contenido ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="table table-hover bg-blanco" cellspacing="0">
    <thead>
        <th style="width: 100px;">Ejecutar</th>
        <th style="width: 20%">Procesos</th>
        <th>Descripción</th>
    </thead>
    <tbody>

        <tr>
            <td><?= anchor('develop/limpiar_paginas', 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td>Limpiar páginas</td>
            <td>
                Elimina las páginas que no se están utilizando en los flipbooks, también las imágenes asociadas.
            </td>
        </tr>

        <tr>
            <td><?= anchor('develop/eliminar_huerfanos', 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td>Eliminar estudiantes huérfanos</td>
            <td>
                Eliminar estudiantes que no pertenecen a ningún grupo.
                Se eliminarán los estudiantes de grupos que fueron eliminados (Proceso provisional).
            </td>
        </tr>

        <tr>
            <td><?= anchor('develop/desbloquear_flipbooks/0', 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td>Desbloquear flipbooks</td>
            <td>Reemplazar carácteres en anotaciones de flipbooks</td>
        </tr>

        <tr>
            <td><?= anchor('develop/grupo_actual', 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td>Actualizar grupo actual</td>
            <td>Establecer usuario.grupo_id para los estudiantes sin grupo actual, pero que están en grupo_usuario.</td>
        </tr>

        <tr>
            <td><?= anchor('develop/desactivar_morosos', 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td>Desactivar morosos</td>
            <td>
                Desactivar la cuenta de los estudiantes que están marcados como "Pago: <span
                    class="resaltar">No</span>". Se desactivan si la fecha actual es posterior
                a la <span class="rojo">fecha de vencimiento</span> de la Institución.
            </td>
        </tr>

        <tr>
            <td><?= anchor('programas/act_campo_temas', 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td>Actualizar campo programa.temas</td>
            <td>
                Actualiza el campo programa.temas según el contenido de la tabla programa_tema.
            </td>
        </tr>

        <tr>
            <td><?= anchor('develop/crear_ev_ctn_existentes', 'Ejecutar', 'class="btn btn-primary"') ?></td>
            <td>Crear eventos de asignación</td>
            <td>
                Crear eventos de cuestionarios ya existentes en la tabla usuario_cuestionario
            </td>
        </tr>
    </tbody>
</table>

<?php if ( $this->session->userdata('rol_id') == 0 ) { ?>
    <table class="table table-hover bg-blanco" cellspacing="0">
        <thead>
            <th style="width: 100px;">Ejecutar</th>
            <th style="width: 20%">Procesos</th>
            <th>Descripción</th>
        </thead>
        <tbody>
            <tr>
                <td><?= anchor('develop/migrar_archivos_v3', 'Ejecutar', 'class="btn btn-primary"') ?></td>
                <td>Tabla [tema]</td>
                <td>Migrar archivos de la tabla tema, a la tabla recurso</td>
            </tr>
            <tr>
                <td><?= anchor('develop/migrar_archivos_v3', 'Ejecutar', 'class="btn btn-primary"') ?></td>
                <td>Tabla [tema]</td>
                <td>Migrar archivos de la tabla tema, a la tabla recurso</td>
            </tr>

            <tr>
                <td><?= anchor('develop/migrar_links_v3', 'Ejecutar', 'class="btn btn-primary"') ?></td>
                <td>Links de la tabla [tema] > [recurso]</td>
                <td>Migrar links de la tabla tema, a la tabla recurso</td>
            </tr>

            <tr>
                <td><?= anchor('cuestionarios/actualizar_areas', 'Ejecutar', 'class="btn btn-primary"') ?></td>
                <td>Actualizar áreas</td>
                <td>Actualizar el campo cuestionario.areas</td>
            </tr>

            <tr>
                <td><?= anchor('develop/archivos_carpeta/', 'Ejecutar', 'class="btn btn-primary"') ?></td>
                <td>Archivos de una carpeta</td>
                <td>Cargar en la tabla z_ref los archivos de una carpeta en assets/uploads/</td>
            </tr>
        </tbody>
    </table>
<?php } ?>