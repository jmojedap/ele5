<?php
    $link_status = base_url("orders/my_suscriptions");
?>


<body>
    <div style="<?= $style->body ?>">
        <table>
            <tr>
                <td colspan="3">
                    <?php if ( $row_order->wompi_status == 'APPROVED' ) : ?>
                        <p style="<?= $style->alert ?>">
                            Nos complace informar que hemos recibido el pago. Estamos preparando su pedido
                            y pronto recibirá el número de guía para hacer seguimiento de la entrega.
                        </p>
                    <?php else: ?>
                        <p>El pago no fue confirmado</p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="width: 33%;">
                    <b style="<?= $style->text_info ?>"><?= $this->pml->money($row_order->amount) ?></b>
                    <span style="<?= $style->text_muted ?>">
                        <?= $this->Item_model->name(7, $row_order->status) ?>
                    </span>
                </td>
                <td style="text-align: left;">
                    <h4 style="<?= $style->h4 ?>"></h4>
                </td>
                <td style="text-align: right;">
                    <a href="<?= base_url("orders/status/{$row_order->order_code}") ?>" style="<?= $style->btn ?>" title="Ver compra en la página" target="_blank">
                        Ver compra
                    </a>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="<?= $style->text_center ?>">
                    <h1 style="<?= $style->h1 ?>">
                        <?= $row_order->buyer_name ?>
                    </h1>
                    
                </td>
            </tr>

            <tr style="<?= $style->text_center ?>">
                <td colspan="3">
                    <span style="<?= $style->text_muted?>">
                        Ref. venta:
                    </span>
                    <span style="<?= $style->text_primary ?>">
                        <?= $row_order->order_code ?>
                    </span>

                    &middot;

                    <span style="<?= $style->text_muted?>">
                        Actualizado:
                    </span>
                    <span style="<?= $style->text_primary ?>">
                        <?= $this->pml->date_format($row_order->updated_at, 'Y-M-d H:i') ?>
                    </span>
                    &middot;

                    <span style="<?= $style->text_muted?>">
                        Valor total:
                    </span>
                    <span style="<?= $style->text_primary ?>">
                        <?= $this->pml->money($row_order->amount) ?>
                    </span>
                </td>
            </tr>
        </table>

        <h2 style="<?= $style->h2 ?>">Detalle de la compra</h2>

        <table style="<?= $style->table ?>">
            <thead style="<?= $style->thead ?>">
                <tr style="">
                    <td style="<?= $style->td ?>">Producto</td>
                    <td style="<?= $style->td ?>">Precio</td>
                    <td style="<?= $style->td ?>">Cantidad</td>
                    <td style="<?= $style->td ?>">
                        <?= $this->pml->money($row_order->amount) ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products->result() as $row_product) : ?>
                    <?php
                        $precio_detalle = $row_product->quantity * $row_product->price;
                    ?>
                    <tr>
                        <td style="<?= $style->td ?>" width="65%">
                            <strong><?= $row_product->name ?></strong>
                            
                            <p>
                                <?= $row_product->description ?>
                            </p>
                        </td>
                        <td style="<?= $style->td ?>">
                            <p>
                                <?= $this->pml->money($row_product->price) ?>
                            </p>
                        </td>
                        <td style="<?= $style->td ?>">
                            <p>
                                <?= $row_product->quantity ?>
                            </p>
                        </td>
                        <td style="<?= $style->td ?>">
                            <?= $this->pml->money($precio_detalle) ?>
                        </td>

                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <h2 style="<?= $style->h2 ?>">Datos de entrega</h2>

        <p>
            

            <span style="<?= $style->text_muted ?>">
                No. documento
            </span>
            <span style="<?= $style->text_primary ?>">
                <?= $row_order->id_number ?>
            </span>

            &middot;

            <span style="<?= $style->text_muted ?>">E-mail</span>
            <span style="<?= $style->text_primary ?>"><?= $row_order->email ?></span>

            &middot;

            <span style="<?= $style->text_muted ?>">
                Ciudad
            </span>
            <span style="<?= $style->text_primary ?>">
                <?= $row_order->city ?>
            </span>

            &middot;

            <span style="<?= $style->text_muted ?>">Dirección</span>
            <span style="<?= $style->text_primary ?>">
                <?= $row_order->address ?>
            </span>            

            &middot;

            <span style="<?= $style->text_muted ?>">
                Teléfono
            </span>
            <span style="<?= $style->text_primary ?>">
                <?= $row_order->phone_number ?>
            </span>

            <span style="<?= $style->text_muted ?>">
                Institución:
            </span>
            <span style="<?= $style->text_primary ?>">
                <?= $this->App_model->nombre_institucion($row_order->institution_id) ?>
            </span>
            <span style="<?= $style->text_muted ?>">
                (<?= $row_order->institution_id ?>)
            </span>

            &middot;
        </p>

        <hr>

        <div style="<?= $style->footer ?>">
            <h3>
                <?= APP_NAME ?> &middot; En Línea Editores &middot; Colombia
            </h3>
            <p sytle="<?= $style->text_muted ?>">
                &copy; <?= date('Y') ?>
            </p>
        </div>

    </div>
</body>