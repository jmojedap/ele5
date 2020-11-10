<div class="row">
    <div class="col-md-5">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td width="30%">Ver en catálogo</td>
                    <td>
                        <a href="<?= base_url("products/details/{$row->id}") ?>" class="btn btn-light">
                            Abrir
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td>Referencia</td>
                    <td><?= $row->code ?></td>
                </tr>
                <tr>
                    <td>Nombre</td>
                    <td><?= $row->name ?></td>
                </tr>
                <tr>
                    <td>Nivel escolar</td>
                    <td><?= $this->Item_model->name(3, $row->level) ?></td>
                </tr>
                <tr>
                    <td>Precio venta</td>
                    <td>
                        <strong class="price_label"><?= $this->pml->money($row->price) ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>% IVA</td>
                    <td><?= $row->tax_percent ?></td>
                </tr>
                <tr>
                    <td>IVA</td>
                    <td><?= $this->pml->money($row->tax) ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Actualizado por</td>
                    <td><?= $this->App_model->nombre_usuario($row->updater_id) ?></td>
                </tr>
                <tr>
                    <td>Actualizado</td>
                    <td><?= $row->updated_at ?></td>
                </tr>
                <tr>
                    <td>Creado por</td>
                    <td><?= $this->App_model->nombre_usuario($row->creator_id) ?></td>
                </tr>
                <tr>
                    <td>Creado</td>
                    <td><?= $row->created_at ?></td>
                </tr>
            </tbody>

        </table>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h2><?= $row->name ?></h2>
                <div>
                    <h4 class="text-muted">Descripción:</h4>
                    <?= $row->description ?>
                </div>
            </div>
        </div>
    </div>
</div>