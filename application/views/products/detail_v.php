<style>
    .price {
        font-family: 'Rubik', sans-serif;
        font-weight: bold;
        font-size: 1.5em;
        color: #89cb4f;
    }
</style>

<script>
// Variables
//-----------------------------------------------------------------------------
var user_id = '<?php echo $this->session->userdata('user_id'); ?>';
var product_id = '<?php echo $row->id; ?>';

// Document Ready
//-----------------------------------------------------------------------------
$(document).ready(function() {

    $('#btn_add_product').click(function()
    {
        add_product(product_id);
    });
});

// Functions
//-----------------------------------------------------------------------------

    /**
    Crear orden con producto */
    function add_product(product_id) {
        $.ajax({
            type: 'POST',
            url: app_url + 'orders/add_product/' + product_id,
            success: function(response) {
                console.log(response.message);
                if (response.status == 1) {
                    window.location = app_url + 'orders/checkout';
                }
            }
        });
    }
</script>

<div class="mb-2">
    <a href="<?= base_url("products/catalog") ?>" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i>
        Volver
    </a>
</div>

<div class="row product_detail">
    <div class="col-md-3">
        <img src="<?= URL_IMG ?>comercial/product_example.jpg" alt="Imagen producto" class="w100pc">
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <h1><?php echo $row->name ?></h1>

                <div class="d-flex">
                    <div class="flex-fill">
                        <p class="price">
                            <?php echo $this->pml->money($row->price); ?>
                        </p>
                    </div>
                    <div class="flex-fill">
                        <button class="btn btn-primary btn-lg" id="btn_add_product">
                            Comprar
                        </button>
                    </div>
                </div>

                <hr>

                <h2>Descripción del producto</h2>
                <p><?php echo $row->description ?></p>

                <h2>Contenidos asociados</h2>
                <table class="table">
                    <tbody>
                        <thead>
                            <th>Título</th>
                            <th>Nivel</th>
                        </thead>
                        <?php foreach ( $flipbooks->result() as $row_flipbook ) { ?>
                            <tr>
                                <td><?php echo $row_flipbook->title ?></td>
                                <td><?= $this->Item_model->name(3, $row_flipbook->nivel) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <ul>
                </ul>
            </div>
        </div>
        <?php if ( $this->session->userdata('role') <= 2  ) { ?>
            <a href="<?= base_url("products/edit/{$row->id}") ?>" class="btn btn-warning w120p">
                <i class="fa fa-edit"></i> Editar
            </a>
        <?php } ?>
    </div>
</div>