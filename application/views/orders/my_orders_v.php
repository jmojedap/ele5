<table class="table bg-white">
    <thead>
        <th>Ref. venta</th>
        <th>Descripción</th>
    </thead>
    <tbody>
        <?php foreach ( $orders->result() as $row_order ) { ?>
            <tr>
                <td>
                    <a href="<?= base_url("orders/status/{$row_order->order_code}") ?>" class="">
                        <?php echo $row_order->order_code ?>
                    </a>
                </td>
                <td>
                    Estado:
                    <b><?php echo $this->Item_model->name(7, $row_order->status); ?></b>
                    <?php if ( $row_order->status == 1 ) { ?>
                        <i class="fa fa-check-circle text-success"></i>
                    <?php } ?>
                    <br>
                    Fecha:
                    <b><?php echo $this->pml->date_format($row_order->updated_at); ?></b>
                    <br>
                    Valor:
                    <b><?php echo $this->pml->money($row_order->amount) ?></b>
                    <br>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>