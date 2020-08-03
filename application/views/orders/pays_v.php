<div id="pays_app">
    <div class="card center_box_750">
        <div class="card-body">
            <h1 class="text-center">Pagos En Línea Editores</h1>

            <div class="mb-2" v-show="step == 1">
                <p class="text-center">Realizar pago</p>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-lg btn-info btn-block" v-on:click="set_code_type('institution')">
                            Por Código de Institución
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn btn-lg btn-primary btn-block" v-on:click="set_code_type('user')">
                            Por Código de Usuario
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="step == 2">
                <button class="btn btn-secondary w120p mb-2" v-on:click="set_step(1)">
                    <i class="fa fa-arrow-left"></i> Atrás
                </button>
                <form accept-charset="utf-8" method="POST" id="institution_form" @submit.prevent="get_institutions" v-show="code_type == 'institution'">
                    <div class="form-group row">
                        <label for="cod" class="col-md-4 col-form-label text-right">Código Institución</label>
                        <div class="col-md-5">
                            <input
                                name="cod" id="field-cod" type="text" class="form-control"
                                required
                                title="Código institución" placeholder=""
                                v-model="institution_cod"
                            >
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" type="submit">
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>
                <table class="table" v-show="code_type == 'institution'">
                    <tbody>
                        <tr v-for="(institution, institution_key) in institutions">
                            <td>{{ institution.name }}</td>
                            <td style="width: 150px;">
                                <button class="btn btn-light btn-block" v-on:click="set_current(institution_key)"> <i class="fa fa-check"></i> Continuar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <form accept-charset="utf-8" method="POST" id="user_form" @submit.prevent="get_user" v-show="code_type == 'user'">
                    <div class="form-group row">
                        <label for="cod" class="col-md-4 col-form-label text-right">Código Usuario</label>
                        <div class="col-md-5">
                            <input
                                name="username" id="field-username" type="text" class="form-control"
                                required
                                title="Código estudiante" placeholder=""
                                v-model="username"
                            >
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" type="submit">
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>
                
            </div>

            <div class="products" v-show="step == 3">
                <button class="btn btn-secondary w120p mb-2" v-on:click="set_step(2)">
                    <i class="fa fa-arrow-left"></i> Atrás
                </button>
                <h2 class="text-success" v-show="user.id > 0">{{ user.nombre }} {{ user.apellidos }}</h2>
                <h2 class="text-success">{{ curr_institution.name }}</h2>
                <h3>Pagos asociados</h3>
                <table class="table">
                    <thead>
                        <th>Nombre</th>
                        <th>Nivel escolar</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="(product, product_key) in products">
                            <td>
                                {{ product.name }}
                            </td>
                            <td>
                                {{ product.level }}
                            </td>
                            <td>{{ product.price | currency }}</td>
                            <td>
                                <button class="btn btn-primary btn-block" v-on:click="add_product(product_key)">
                                    Pagar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
// Filters
//-----------------------------------------------------------------------------
    Vue.filter('currency', function (value) {
        if (!value) return '';
        value = '$ ' + new Intl.NumberFormat().format(value);
        return value;
    });

// Vue Application
//-----------------------------------------------------------------------------
    new Vue({
        el: '#pays_app',
        created: function(){
            //this.get_list();
        },
        data: {
            step: 1,
            code_type: 'user',
            institutions: [],
            institution_cod: 'EE0041',
            curr_institution: {
                id: 0,
                name: ''
            },
            username: 'kdquinto7',
            user: { id: 0 },
            level: '',
            products: []
        },
        methods: {
            set_step: function(step){
                this.step = step;
            },
            set_code_type: function(code_type){
                this.code_type = code_type;
                this.step = 2;
            },
            get_institutions: function(){
                axios.post(app_url + 'instituciones/get_by_cod/' + this.institution_cod)
                .then(response => {
                    this.institutions = response.data.list;
                    this.user.id = 0;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            set_current: function(institution_key){
                this.curr_institution = this.institutions[institution_key];
                this.get_products();
            },
            get_user: function(){
                axios.post(app_url + 'usuarios/get_by_username/', $("#user_form").serialize())
                .then(response => {
                    if ( response.data.users.length > 0 )
                    {
                        this.user = response.data.users[0];
                        this.curr_institution = {
                            id: this.user.institucion_id,
                            name: this.user.nombre_institution
                        }
                        this.level = this.user.level;

                        this.get_products();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            get_products: function(){
                axios.get(app_url + 'products/get_by_institution/' + this.curr_institution.id + '/' + this.level)
                .then(response => {
                    this.products = response.data.list;
                    this.step = 3;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            //Crear orden de compra, y agregar producto, relacionar institución y usuario, si están disponibles los datos
            add_product: function(product_key){
                var product_id = this.products[product_key].id;
                var str_get = '/?i=' + this.curr_institution.id + '&n=' + this.products[product_key].level;
                if ( this.user.id > 0 ) { str_get += '&u=' + this.user.id; }
                axios.get(app_url + 'orders/add_product/' + product_id + str_get)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = app_url + 'orders/checkout';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>