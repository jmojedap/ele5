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
            this.start_step();
        },
        data: {
            step: 2,
            code_type: 'institution',
            institutions: [],
            institution_cod: '<?= $institution_cod ?>',
            curr_institution: <?= json_encode($curr_institution) ?>,
            username: 'kdquinto7',
            user: { id: 0 },
            level: '',
            products: []
        },
        methods: {
            start_step: function(){
                console.log(this.curr_institution);
                if ( this.curr_institution.id > 0 )
                {
                    this.step = 3
                    this.get_products()
                }  
            },
            set_step: function(step){
                this.step = step;
            },
            set_code_type: function(code_type){
                this.code_type = code_type;
                this.step = 2;
            },
            get_institutions: function(){
                axios.post(url_app + 'instituciones/get_by_cod/' + this.institution_cod)
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
                axios.post(url_app + 'usuarios/get_by_username/', $("#user_form").serialize())
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
                axios.get(url_app + 'products/get_by_institution/' + this.curr_institution.id + '/' + this.level)
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
                axios.get(url_app + 'orders/add_product/' + product_id + str_get)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = url_app + 'orders/checkout';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>