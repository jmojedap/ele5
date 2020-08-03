<div class="row">
    <div class="col-md-5">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td width="30%">Ver en catálogo</td>
                    <td>
                        <a href="<?= base_url("products/detail/{$row->id}") ?>" class="btn btn-light">
                            Abrir
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><?php echo $row->id ?></td>
                </tr>
                <tr>
                    <td>Nombre</td>
                    <td><?php echo $row->name ?></td>
                </tr>
                <tr>
                    <td>Kit</td>
                    <td>
                        <a href="<?= base_url("kits/instituciones/{$row->kit_id}") ?>">    
                            <?php echo $this->Db_model->field_id('kit', $row->kit_id, 'nombre_kit') ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Nivel escolar</td>
                    <td><?php echo $this->Item_model->name(3, $row->level) ?></td>
                </tr>
                <tr>
                    <td>Precio venta</td>
                    <td><?php echo $this->pml->money($row->price) ?></td>
                </tr>
                <tr>
                    <td>% IVA</td>
                    <td><?php echo $row->tax_percent ?></td>
                </tr>
                <tr>
                    <td>IVA</td>
                    <td><?php echo $this->pml->money($row->tax) ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Actualizado por</td>
                    <td><?php echo $this->App_model->nombre_usuario($row->updater_id) ?></td>
                </tr>
                <tr>
                    <td>Actualizado</td>
                    <td><?php echo $row->updated_at ?></td>
                </tr>
                <tr>
                    <td>Creado por</td>
                    <td><?php echo $this->App_model->nombre_usuario($row->creator_id) ?></td>
                </tr>
                <tr>
                    <td>Creado</td>
                    <td><?php echo $row->created_at ?></td>
                </tr>
            </tbody>

        </table>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h2><?php echo $row->name ?></h2>
                <div>
                    <h4 class="text-muted">Descripción:</h4>
                    <?php echo $row->description ?>
                </div>
            </div>
        </div>
    </div>
</div>