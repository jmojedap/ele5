<h3 class="section_title">Detalle productos ( <?= $products->num_rows() ?> )</h3>
<table class="table bg-white">
    <thead>
        <th>Producto</th>
        <th>Precio</th>
    </thead>
    <tbody>
        <?php foreach ( $products->result() as $product ) { ?>
            <tr>
                <td>
                    <a href="<?php echo base_url("products/details/{$product->product_id}") ?>" class="">
                        <?php echo $product->name ?>
                    </a>
                </td>
                <td><?php echo $this->pml->money($product->price * $product->quantity) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>