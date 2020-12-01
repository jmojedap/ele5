<?php
    //Formulario destino
    $url_action = 'https://checkout.wompi.co/p/';
    if ( $form_data['test'] == 1 ) { $url_action = 'https://checkout.wompi.co/p/'; }
?>

<div class="px-3 py-1 mx-auto text-center">
    <h1 class="display-4">Verifica</h1>
</div>

<?php $this->load->view('orders/checkout/steps_v') ?>

<div class="center_box_750">
    <table class="table bg-white">
        <tbody>
            <tr>
                <td>Institución</td>
                <td>
                    <?= $this->App_model->nombre_institucion($row->institution_id); ?>
                </td>
            </tr>
            <tr>
                <td>Nivel escolar</td>
                <td><?= $this->Item_model->name(3, $row->level, 'item_largo'); ?></td>
            </tr>

            <?php if ( $row->user_id > 0 ) { ?>
                <tr>
                    <td>Usuario estudiante</td>
                    <td class="">
                        <?= $this->App_model->nombre_usuario($row->user_id, 'nau'); ?>
                    </td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td>Nombre estudiante</td>
                    <td><?= $row->student_name ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td>Nombre comprador</td>
                <td><?php echo $row->buyer_name ?></td>
            </tr>

            <tr>
                <td>Correo electrónico</td>
                <td><?php echo $row->email ?></td>
            </tr>

            <tr>
                <td>Ciudad</td>
                <td>
                    <?php echo $row->city; ?>
                </td>
            </tr>

            <tr>
                <td>Dirección</td>
                <td>
                    <?php echo $row->address; ?>
                </td>
            </tr>

            <tr>
                <td>Teléfono</td>
                <td><?php echo $row->phone_number ?></td>
            </tr>

            <tr>
                <td>Valor total</td>
                <td class="td_price">
                    <?php echo $this->pml->money($row->amount) ?>
                    <small>COP</small>
                </td>
            </tr>
        </tbody>
    </table>

    <form method="GET" action="<?php echo $url_action ?>">
        <!-- OBLIGATORIOS -->
        <input type="hidden" name="public-key" value="<?= $form_data['public-key'] ?>" />
        <input type="hidden" name="currency" value="<?= $form_data['currency'] ?>" />
        <input type="hidden" name="amount-in-cents" value="<?= $form_data['amount-in-cents'] ?>" />
        <input type="hidden" name="reference" value="<?= $form_data['reference'] ?>" />
        <!-- OPCIONALES -->
        <input type="hidden" name="redirect-url" value="<?= $form_data['redirect-url'] ?>" />

        <button class="btn btn-success btn-block btn-lg">
            PAGAR
        </button>
    </form>
    <a class="btn btn-info btn-block mt-2" role="button" href="<?php echo base_url('orders/checkout/1') ?>">
        <i class="fa fa-chevron-left"></i>
        Volver
    </a>
    <hr>
    <?php $this->load->view('orders/checkout/products_v') ?>
</div>