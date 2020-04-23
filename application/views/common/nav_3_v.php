<div id="nav_3_vue" class="mb-2">
    <div class="d-none d-sm-block">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item" v-for="(element, key) in nav_3">
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
        el: '#nav_3_vue',
        data: {
            nav_3: nav_3  //Elementos contenido del menú
        },
        methods: {
            activate_menu: function (key) {
                for ( i in this.nav_3 ){
                    this.nav_3[i].class = '';
                }
                this.nav_3[key].class = 'active';   //Elemento actual
                this.load_view_a(key);
            },
            load_view_a: function(key){
                app_cf = this.nav_3[key].cf;
                console.log(app_cf);
                load_sections('nav_3'); //Función global
            }
        }
    });
</script>