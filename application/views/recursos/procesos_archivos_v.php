<?php
    $carpeta_iconos = RUTA_IMG . 'flipbook/';
?>

<?php $this->load->view('recursos/menu_archivos_v') ?>

<?php if ( strlen($this->session->flashdata('mensaje')) > 0 ) { ?>
    <div class="alert alert-info">
        <?= $this->session->flashdata('mensaje') ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col col-md-6">
        <table class="table bg-white">
            <thead>
                <th width="50px"></th>
                <th>Carpeta</th>
                <th>Asociar</th>
                <th>Disponible</th>
                <th>Cambiar nombre</th>
            </thead>
            <tbody>
                <?php foreach ($carpetas->result() as $row_carpeta) : ?>
                    <?php
                        $src = $carpeta_iconos . $row_carpeta->slug . '.png';
                    ?>
                    <tr>
                        <td><?= img($src) ?></td>
                        <td><?= $row_carpeta->slug ?></td>
                        <td><?= anchor("recursos/asociar_archivos_e/{$row_carpeta->id}", '<i class="fa fa-sync"></i>', 'class="btn btn-light"') ?></td>
                        <td><?= anchor("recursos/act_archivos_disponibles/{$row_carpeta->id}", '<i class="fa fa-check"></i>', 'class="btn btn-light"') ?></td>
                        <td><?= anchor("recursos/cambiar_nombres_e/{$row_carpeta->id}", '<i class="fa fa-font"></i> |', 'class="btn btn-light"') ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="col col-md-6">
        <div class="card">
            <div class="card-header">
                Descripción de los procesos
            </div>
            <div class="card-body">
                <h3>Asociar</h3>
                <h4>Asociación automática de archivos</h4>

                <ul>
                    <li>
                        Se puede asociar <span class="resaltar">automáticamente</span> los archivos cargados a la plataforma.
                    </li>
                    <li>
                        La asociación se hace comparando los seis primeros caracteres del <span class="resaltar">nombre del archivo</span> con el <span class="resaltar">código del tema</span>.
                    </li>
                    <li>
                        Por ejemplo el archivo 'm2-001f.mp4' se asociará con el tema con código 'm2-001'.
                    </li>
                </ul>
                
                <hr/>

                <h3>Disponible</h3>
                <h4>Marcar achivos disponibles</h4>

                <ul>
                    <li>
                        Se verifica si el archivo asignado a un tema está disponible o no en la plataforma.
                    </li>
                    <li>
                        Si el archivo no está disponible, no se mostrará el link en el Flipbook.
                    </li>
                </ul>

                <hr/>

                <h3>Cambiar nombre</h3>
                <h4>Cambiar nombre de archivos</h4>

                <ul>
                    <li>
                        Cambiar el nombre de los archivos que ya están asignados a los temas.
                    </li>
                    <li>
                        Ejemplo: Si el archivo "un_archivo.mp3" está asignado al tema con código "m1-001", se cambia a "m1-001a.mp3".
                    </li>
                    <li>
                        Es una función de transición entre las versiones V2 a V3 de la Plataforma Enlace.
                    </li>
                </ul>
            </div>
        </div>
        

        
    </div>
</div>





