<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td></td>
                    <td>
                        <a href="<?= base_url("posts/open/{$row->id}") ?>" class="btn btn-light w120p">
                            Abrir
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><?php echo $row->id ?></td>
                </tr>
                <tr>
                    <td>Tipo</td>
                    <td><?php echo $this->Item_model->name(33, $row->tipo_id) ?></td>
                </tr>
                <tr>
                    <td>Nombre post</td>
                    <td><?php echo $row->nombre_post ?></td>
                </tr>
                <tr>
                    <td>Estado ID</td>
                    <td><?php echo $row->estado_id ?></td>
                </tr>
                <tr>
                    <td>slug</td>
                    <td><?php echo $row->slug ?></td>
                </tr>
                <tr>
                    <td>imagen id</td>
                    <td><?php echo $row->imagen_id ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>publicado</td>
                    <td><?php echo $row->publicado ?></td>
                </tr>
                <tr>
                    <td>editor_id</td>
                    <td><?php echo $row->editor_id ?></td>
                </tr>
                <tr>
                    <td>editado</td>
                    <td><?php echo $row->editado ?></td>
                </tr>
                <tr>
                    <td>usuario_id</td>
                    <td><?php echo $row->usuario_id ?></td>
                </tr>
                <tr>
                    <td>creado</td>
                    <td><?php echo $row->creado ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?php echo $row->nombre_post ?></h2>
                <div>
                    <h4 class="text-muted">contenido</h4>
                    <?php echo $row->resumen ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">contenido</h4>
                    <?php echo $row->contenido ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">contenido json</h4>
                    <?php echo $row->contenido_json ?>
                </div>
            </div>
        </div>
    </div>
</div>