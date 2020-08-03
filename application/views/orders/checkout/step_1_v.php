<?php //$this->load->view('assets/select2') ?>

<?php
    //$options_country = $this->App_model->options_place('type_id = 2 AND active = 1', 'full_name', 'País');
    $options_city = $this->App_model->options_place('tipo_id = 4', 'cr', 'Ciudad');
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var order_id = <?php echo $row->id; ?>;

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#checkout_form').submit(function(){
            update_order();
            return false;
        });
    });

// Functions
//-----------------------------------------------------------------------------
    function update_order(){
        $.ajax({        
            type: 'POST',
            url: app_url + 'orders/update/' + order_id,
            data: $('#checkout_form').serialize(),
            success: function(response){
                console.log(response.message);
                if ( response.status == 1 ) { window.location = app_url + 'orders/checkout/2'; }
            }
        });
    }
</script>

<div class="mb-3 mx-auto text-center">
    <h1 class="display-4">Tus datos</h1>
    <p class="lead">
        Completa los datos requeridos por
        <a href="https://www.payulatam.com/co/compradores/" target="_blank">PayU</a>
        para realizar la compra
    </p>
</div>

<?php $this->load->view('orders/checkout/steps_v') ?>

<div class="center_box_750">
    

    

    <div class="">

        <form accept-charset="utf-8" method="POST" id="checkout_form">
            <div class="form-group row">
                <label for="buyer_name" class="col-md-3 col-form-label">Nombre comprador</label>
                <div class="col-md-9">
                    <input
                        name="buyer_name" id="field-buyer_name" type="text" class="form-control"
                        required
                        title="Nombre completo" placeholder="Nombre completo"
                        value="<?= $row->buyer_name ?>"
                    >
                </div>
            </div>

            <div class="form-group row">
                <label for="id_number" class="col-md-3 col-form-label">No. documento</label>
                <div class="col-md-9">
                    <input
                        name="id_number" id="field-id_number" type="text" class="form-control"
                        required
                        value="<?= $row->id_number ?>"
                    >
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label">Correo electrónico</label>
                <div class="col-md-9">
                    <input
                        name="email" id="field-email" type="text" class="form-control"
                        required
                        title="Correo electrónico"
                        value="<?= $row->email ?>"
                    >
                </div>
            </div>

            <div class="form-group row">
                <label for="city_id" class="col-md-3 col-form-label">Ciudad</label>
                <div class="col-md-9">
                    <?php echo form_dropdown('city_id', $options_city, $row->city_id, 'id="field-city_id" class="form-control" required') ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-md-3 col-form-label">Dirección</label>
                <div class="col-md-9">
                    <input
                        id="field-address"
                        name="address"
                        class="form-control"
                        required
                        value="<?php echo $row->address ?>"
                        type="text"
                        title="Escribe tu dirección"
                        >
                </div>
            </div>

            <div class="form-group row">
                <label for="phone_number" class="col-md-3 col-form-label">Teléfono</label>
                <div class="col-md-9">
                    <input
                        id="field-phone_number"
                        name="phone_number"
                        class="form-control"
                        required
                        minlength="7"
                        value="<?php echo $row->phone_number ?>"
                        type="text"
                        title="Escribe tu número de teléfono"
                        >
                </div>
            </div>

            <?php if ( $row->user_id == 0 ) { ?>
                <div class="form-group row">
                    <label for="student_name" class="col-md-3 col-form-label">Nombre estudiante</label>
                    <div class="col-md-9">
                        <input
                            name="student_name" id="field-student_name" type="text" class="form-control"
                            required
                            title="" placeholder=""
                            value="<?= $row->student_name ?>"
                        >
                    </div>
                </div>
            <?php } ?>

            
            <div class="form-group row">
                <div class="col-md-9 offset-md-3 text-center">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">
                        CONTINUAR
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-6_no">
        
        <h3 class="section_title">Datos de la compra</h3>
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Institución</td>
                    <td class="">
                        <?= $this->App_model->nombre_institucion($row->institution_id); ?>
                    </td>
                </tr>
                <tr>
                    <td>Nivel escolar</td>
                    <td class="">
                        <?= $this->Item_model->name(3, $row->level, 'item_largo'); ?>
                    </td>
                </tr>
                <?php if ( $row->user_id > 0 ) { ?>
                    <tr>
                        <td>Usuario estudiante</td>
                        <td class="">
                            <?= $this->App_model->nombre_usuario($row->user_id, 'nau'); ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Valor total</td>
                    <td class="td_price">
                        <?php echo $this->pml->money($row->amount) ?>
                        <small>COP</small>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php $this->load->view('orders/checkout/products_v') ?>
    </div>
    
</div>