<?php
    $elements['icon'] = 'fa fa-check-circle';
    $elements['class'] = 'text-success';

    if ( ! $success )
    {
        $success = FALSE;
        $elements['icon'] = 'fa fa-info-circle';
        $elements['class'] = 'text-info';
    }
?>

<div class="center_box_750 vbn_post">
    <div class="text-center">
        <h1 class="<?php echo $elements['class'] ?>">
            <i class="<?php echo $elements['icon'] ?>"></i>
            <?php echo $head_title ?>
        </h1>
    </div>

    <div class="mb-2">
        <?php $this->load->view('orders/checkout/steps_v') ?>
    </div>

    <h2 class="post_title text-center">Resumen transacción</h3>

    <table class="table bg-white">
        <tbody>
            <tr class="table-info">
                <td class="text-right" width="40%">Ref. venta</td>
                <td><?php echo $result->reference; ?></td>
            </tr>
            <tr>
                <td class="text-right">Fecha transacción</td>
                <td><?php echo $result->created_at; ?></td>
            </tr>
            <tr>
                <td class="text-right">Referencia Transacción Wompi</td>
                <td><?php echo $result->id; ?></td>
            </tr>
            <tr>
                <td class="text-right">Medio de pago</td>
                <td><?php echo $result->payment_method->type; ?></td>
            </tr>
            
            <tr>
                <td class="text-right">Valor</td>
                <td>
                    <?php echo $this->pml->money($result->amount_in_cents/100); ?>
                    <small>
                        <?php echo $result->currency; ?>
                    </small>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="height: 150px;"></div>
</div>
