<style>
    .catalog .product_description{
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        display: inline-block;
        text-overflow: ellipsis;
        border: 1px solid #ddd;
        margin: 0;
    }
</style>

<div id="app_catalog" class="catalog">
    <form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
        <div class="row mb-2">
            <div class="col-md-12">
                <input
                    name="q" id="field-q" type="text" class="form-control" required
                    title="Buscar libro" placeholder="Buscar..."
                >
            </div>
        </div>
    </form>

    <div class="mb-2">
        <button class="btn btn-secondary" v-on:click="sum_page(-1)" title="Página anterior">
            <i class="fa fa-caret-left"></i> Anterior
        </button>
        <button class="btn btn-secondary float-right" v-on:click="sum_page(1)" title="Página siguiente">
            Siguiente <i class="fa fa-caret-right"></i>
        </button>
    </div>

    <table class="table bg-white">
        <tbody>
            <tr v-for="(product, product_key) in list" class="product">
                <td width="120px">
                    <img src="<?= URL_IMG ?>comercial/product_example.jpg" alt="Imagen producto" class="w120p">
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url("products/detail/") ?>` + product.id" class="product_title">
                        {{ product.name }}
                    </a>
                    <p>{{ product.description }}</p>
                    <p class="product_price mb-2">{{ product.price | currency }}</p>
                    <button class="btn btn-primary btn-sm" v-on:click="add_product(product_key)">
                        <i class="fa fa-shopping-cart"></i>
                        Al carrito
                    </button>
                </td>                
            </tr>
        </tbody>
    </table>
</div>

<script>
    Vue.filter('currency', function (value) {
        if (!value) return '';
        value = '$ ' + new Intl.NumberFormat().format(value);
        return value;
    });

// App
//-----------------------------------------------------------------------------
    new Vue({
        el: '#app_catalog',
        created: function(){
            this.get_list();
        },
        data: {
            cf: 'products/catalog/',
            controller: 'products',
            user_id: '<?php echo $this->session->userdata('user_id') ?>',
            product_id: 0,
            list: [],
            num_page: 1,
            max_page: 1,
        },
        methods: {
            get_list: function(){
                axios.post(app_url + this.controller + '/get_catalog/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    //$('#head_subtitle').html(response.data.search_num_rows);
                    history.pushState(null, null, app_url + this.cf  + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            add_product: function(product_key){
                this.product_id = this.list[product_key].id;
                axios.get(app_url + 'orders/add_product/' + this.product_id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = app_url + 'orders/checkout';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            sum_page: function(sum){
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            }
        }
    });
</script>