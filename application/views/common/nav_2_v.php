<div id="nav_2_vue" class="mb-2">
    <div class="d-none d-sm-block">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
            <li class="nav-item" v-for="(element, key) in nav_2">
                <a
                    class="nav-link"
                    href="#"
                    v-bind:class="element.class"
                    v-on:click="activate_menu(key)"
                >
                    <i v-bind:class="element.icon"></i>
                    {{ element.text }}
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    new Vue({
        el: '#nav_2_vue',
        data: {
            nav_2: nav_2  //Elementos contenido del menú
        },
        methods: {
            activate_menu: function (key) {
                for ( i in this.nav_2 ){
                    this.nav_2[i].class = '';
                }
                this.nav_2[key].class = 'active';   //Elemento actual
                if ( this.nav_2[key].anchor ) {
                    window.location = url_app + this.nav_2[key].cf;
                } else {
                    this.load_view_a(key);
                }
            },
            load_view_a: function(key){
                app_cf = this.nav_2[key].cf;
                console.log(app_cf);
                load_sections('nav_2'); //Función global
            }
        }
    });
</script>