<div id="info_app">
    <div class="row">
        <div class="col-md-4">
            <h3>General</h3>
            <div class="card">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Ref Venta</td>
                            <td><?= $row->order_code; ?></td>
                        </tr>
                        <tr>
                            <td>Usuario</td>
                            <td>
                                <a href="<?= base_url("usuarios/actividad/{$row->user_id}") ?>">
                                    <?= $this->App_model->nombre_usuario($row->user_id); ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Comprador</td>
                            <td>
                                <?= $row->buyer_name ?>
                            </td>
                        </tr>
                        <tr>
                            <td>No. documento</td>
                            <td>
                                <?= $row->id_number ?>
                            </td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td><?= $row->email ?></td>
                        </tr>
                        <tr>
                            <td>Valor total</td>
                            <td><strong><?= $this->pml->money($row->amount); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Actualizada en</td>
                            <td><?= $this->pml->date_format($row->updated_at); ?> &middot; <?= $this->pml->ago($row->updated_at); ?></td>
                        </tr>
                        <tr>
                            <td>Creada en</td>
                            <td><?= $this->pml->date_format($row->created_at); ?> &middot; <?= $this->pml->ago($row->created_at); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Envío</h3>
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td>Ciudad</td>
                        <td><?= $this->App_model->nombre_lugar($row->city_id, 'CR') ?></td>
                    </tr>
                    <tr>
                        <td>Dirección</td>
                        <td><?= $row->address ?></td>
                    </tr>
                    <tr>
                        <td>Teléfono</td>
                        <td><?= $row->phone_number ?></td>
                    </tr>
                    <tr>
                        <td>Peso</td>
                        <td><?= $row->total_weight ?> kg</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h3>Gestión</h3>
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td>Estado</td>
                        <td><?= $this->Item_model->name(7, $row->status) ?></td>
                    </tr>
                    <tr>
                        <td>Estado Wompi</td>
                        <td><?=  $row->wompi_status ?></td>
                    </tr>
                    <tr>
                        <td>Wompi ID</td>
                        <td><?=  $row->wompi_id ?></td>
                    </tr>
                    <tr>
                        <td>No. factura</td>
                        <td><?= $row->bill ?></td>
                    </tr>
                    <tr>
                        <td>Notas internas</td>
                        <td><?= $row->notes_admin ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table bg-white">
                <thead>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </thead>
                <tbody>
                    <?php foreach ( $products->result() as $row_product ) { ?>
                        <tr>
                            <td>
                                <a href="<?= base_url("products/info/{$row_product->product_id}") ?>">
                                    <?= $row_product->name ?>
                                </a>
                            </td>
                            <td><?= $row_product->quantity ?></td>
                            <td><?= $this->pml->money($row_product->price) ?></td>
                            <td><?= $this->pml->money($row_product->price * $row_product->quantity) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#info_app',
        created: function(){
            //this.get_list();
        },
        data: {
            order_id: <?= $row->id ?>
        },
        methods: {
            send_status_email: function(){
                axios.get(url_api + 'orders/send_status_email/' + this.order_id)
                .then(response => {
                    toastr['info'](response.data.message)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>