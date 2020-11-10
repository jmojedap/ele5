<script>
// Variables
//-----------------------------------------------------------------------------
var user_id = '<?= $this->session->userdata('user_id'); ?>';
var product_id = '<?= $row->id; ?>';

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

<div class="product_detail center_box_750">
    <div class="card">
        <div class="card-body">
            <h1><?= $row->name ?></h1>

            <div class="d-flex">
                <div class="flex-fill">
                    <p class="price">
                        <?= $this->pml->money($row->price); ?>
                    </p>
                </div>
                <div class="flex-fill">
                    <button class="btn btn-primary btn-lg" id="btn_add_product">
                        Comprar
                    </button>
                </div>
            </div>

            <hr>

            <h2>Descripción</h2>
            <p><?= $row->description ?></p>
        </div>
    </div>
    <?php if ( $this->session->userdata('role') <= 2  ) { ?>
        <a href="<?= base_url("products/edit/{$row->id}") ?>" class="btn btn-warning w120p">
            <i class="fa fa-edit"></i> Editar
        </a>
    <?php } ?>
</div>