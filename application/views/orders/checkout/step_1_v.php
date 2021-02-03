<div class="mb-3 mx-auto text-center">
    <h1 class="display-4">Tus datos</h1>
    <p class="lead">
        Completa los datos requeridos por
        <a href="https://wompi.co/" target="_blank">Wompi</a>
        para realizar el pago
    </p>
</div>

<?php $this->load->view('orders/checkout/steps_v') ?>

<div class="center_box_750" id="step_1">
    <button class="btn btn-primary d-none" v-on:click="set_data_test">
        Datos de prueba
    </button>
    <form accept-charset="utf-8" method="POST" id="checkout_form" @submit.prevent="send_form">
        <div class="form-group row">
            <label for="buyer_name" class="col-md-3 col-form-label">Nombre comprador</label>
            <div class="col-md-9">
                <input
                    name="buyer_name" id="field-buyer_name" type="text" class="form-control"
                    required
                    title="Nombre completo" placeholder="Nombre completo"
                    v-model="order.buyer_name"
                >
            </div>
        </div>

        <div class="form-group row">
            <label for="id_number" class="col-md-3 col-form-label">No. documento</label>
            <div class="col-md-9">
                <input
                    name="id_number" id="field-id_number" type="text" class="form-control"
                    required
                    v-model="order.id_number"
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
                    v-model="order.email"
                >
            </div>
        </div>

        <div class="form-group row">
            <label for="city_id" class="col-md-3 col-form-label">Departamento</label>
            <div class="col-md-9">
                <select name="region_id" v-model="region_id" class="form-control" required v-on:change="get_cities">
                    <option v-for="(region_name, region_key) in options_region" v-bind:value="region_key">{{ region_name }}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="city_id" class="col-md-3 col-form-label">Ciudad</label>
            <div class="col-md-9">
                <select name="city_id" v-model="city_id" class="form-control" required>
                    <option v-for="(city_name, city_key) in options_city" v-bind:value="city_key">{{ city_name }}</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="address" class="col-md-3 col-form-label">Dirección</label>
            <div class="col-md-9">
                <input
                    type="text" id="field-address" name="address" class="form-control"
                    required
                    title="Escribe tu dirección"
                    v-model="order.address"
                    >
            </div>
        </div>

        <div class="form-group row">
            <label for="phone_number" class="col-md-3 col-form-label">Teléfono</label>
            <div class="col-md-9">
                <input
                    id="field-phone_number" name="phone_number"
                    class="form-control"
                    required
                    minlength="7"
                    v-model="order.phone_number"
                    type="text"
                    title="Escribe tu número de teléfono"
                    >
            </div>
        </div>

        <div class="form-group row" v-if="order.user_id == 0">
            <label for="student_name" class="col-md-3 col-form-label">Nombre estudiante</label>
            <div class="col-md-9">
                <input
                    name="student_name" id="field-student_name" type="text" class="form-control"
                    required title="" placeholder=""
                    v-model="order.student_name"
                >
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-md-9 offset-md-3 text-center">
                <button class="btn btn-primary btn-lg btn-block" type="submit">
                    CONTINUAR
                </button>
            </div>
        </div>
    </form>

    <div class="">
        
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
                        <strong class="text-success">{{ order.amount | currency }}</strong>
                        <small>COP</small>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="section_title">Detalle productos ( <?= $products->num_rows() ?> )</h3>
        <table class="table bg-white">
            <thead>
                <th>Producto</th>
                <th>Precio</th>
                <th width="20px"></th>
            </thead>
            <tbody>
                <tr v-for="(product, product_key) in products">
                    <td>
                        <a v-bind:href="`<?php echo base_url("products/details/") ?>` + product.product_id" class="">
                            {{ product.name }}
                        </a>
                    </td>
                    <td class="text-right">{{ product.price * product.quantity | currency }}</td>
                    <td>
                        <button class="a4" data-toggle="modal" data-target="#delete_modal" v-on:click="set_product(product.product_id)">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <a href="<?= base_url("orders/pays/{$institucion->cod}") ?>" class="btn btn-light">
            <i class="fa fa-arrow-left"></i>
            Agregar más productos
        </a>
        <button class="btn btn-warning" data-toggle="modal" data-target="#cancel_modal" title="Cancelar la compra">
            <i class="fa fa-trash-alt mr-2"></i> Vaciar carrito
        </button>
    </div>
    
    <?php $this->load->view('common/modal_single_delete_v') ?>
    <?php $this->load->view('orders/checkout/modal_cancel_v') ?>

</div>

<script>
// Filters
//-----------------------------------------------------------------------------
    Vue.filter('currency', function (value) {
        if (!value) return '';
        value = '$ ' + new Intl.NumberFormat().format(value);
        return value;
    });
// Vue App
//-----------------------------------------------------------------------------
    new Vue({
        el: '#step_1',
        data: {
            order: <?= json_encode($row) ?>,
            order_id: '<?= $row->id ?>',
            product_id: 0,
            region_id: '0<?= $row->region_id ?>',
            city_id: '0<?= $row->city_id ?>',
            options_region: <?= json_encode($options_region) ?>,
            options_city: <?= json_encode($options_city) ?>,
            products: <?= json_encode($products->result()) ?>
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'orders/update/' + this.order_id, $('#checkout_form').serialize())
                .then(response => {
                    console.log(response.data.message);
                    if ( response.data.status == 1 ) { window.location = url_app + 'orders/checkout/2'; }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            get_cities: function(){
                form_data = new FormData
                form_data.append('value_field', 'place_name')
                form_data.append('empty_text', 'Seleccione la ciudad')
                form_data.append('type', '4')
                form_data.append('region_id', this.region_id)
                axios.post(url_api + 'app/get_places/', form_data)
                .then(response => {
                    this.city_id = ''
                    this.options_city = response.data.list
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            set_product: function(product_id){
                this.product_id = product_id  
            },
            delete_element: function(){
                console.log('eliminando producto');
                axios.get(url_api + 'orders/remove_product/' + this.product_id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['info']('El producto fue retirado de tu compra')
                        this.get_order_info()
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            get_order_info: function(){
                axios.get(url_api + 'orders/get_info/' + this.order.order_code)
                .then(response => {
                    this.order = response.data.row
                    this.products = response.data.products
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            set_data_test: function(){
                this.order = data_test
                this.city_id = data_test.city_id
            },
            cancel_order: function(){
                axios.get(url_api + 'orders/cancel/')
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = url_app + 'orders/pays'
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },

        }
    });
</script>