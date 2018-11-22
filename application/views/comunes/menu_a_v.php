<div id="menu_a_vue">
    <div class="d-none d-sm-block">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist" style="margin-bottom: 10px">
            <li class="nav-item" v-for="(elemento, key) in elementos">
                <a
                    class="nav-link"
                    href="#"
                    v-bind:class="elemento.clase"
                    v-on:click="activar_menu(key)"
                >
                    <i v-bind:class="elemento.icono"></i>
                    {{ elemento.texto }}
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    new Vue({
        el: '#menu_a_vue',
        data: {
            elementos: elementos_menu_a
        },
        methods: {
            activar_menu: function (key) {
                for ( i in this.elementos ){
                    this.elementos[i].clase = '';
                }
                this.elementos[key].clase = 'active';   //Elemento actual
                this.cargar_vista_a(key);
            },
            cargar_vista_a: function(key){
                app_cf = this.elementos[key].cf;
                var url_vista_a = app_url + app_cf;
                axios.get(url_vista_a + '?json=1')
                .then(function (response) {
                    $('#vista_a').html(response.data.vista_a);
                    history.pushState(null, null, url_vista_a);
                });
            }
        }
    });
</script>