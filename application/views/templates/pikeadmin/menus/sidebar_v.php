<div class="left main-sidebar" >
    <div class="sidebar-inner leftscroll">
        <div id="sidebar-menu">
            <ul>
                <li v-for="(elemento, i) in elementos" v-bind:class="{ submenu: elemento.submenu }">
                    <a href="#" v-on:click="act_contenido(i,-1)" v-bind:class="{ subdrop: elemento.activo }">
                        <i v-bind:class="elemento.icono"></i>
                        <span>{{ elemento.texto }}</span>
                        <span class="menu-arrow" v-if="elemento.submenu"></span>
                    </a>
                    <ul class="list-unstyled" v-if="elemento.submenu" v-bind:style="elemento.style">
                        <li v-for="(subelemento, j) in elemento.subelementos">
                            <a href="#" v-on:click="act_contenido(i,j)" v-bind:class="{ subdrop: subelemento.activo }">
                                <i v-bind:class="subelemento.icono"></i>
                                {{ subelemento.texto }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>  
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php
    //Script con variables para la construcción del menú lateral
    $this->load->view('plantillas/pikeadmin/menus/elementos_0');
?>

<script>
    new Vue({
        el: '#sidebar-menu',
        created: function() {
            this.menu_inicial();
        },
        data: {
            elementos: elementos_sidebar
        },
        methods: {
            act_contenido: function(i,j) {
                //Identificar CF
                app_cf = this.elementos[i].cf;
                if ( j >= 0) {
                    app_cf = this.elementos[i].subelementos[j].cf;
                }
                
                history.pushState(null, null, app_url + app_cf);
                this.act_vistas();
            },
            //Actualizar secciones de la página
            act_vistas: function() {
                axios.get(app_url + app_cf + '?json=1')
                .then(response => {
                    console.log(response.data.titulo_pagina);
                    $('#titulo_pagina').html(response.data.titulo_pagina);
                    $('#vista_a').html(response.data.vista_a);
                    $('#menu_a').html(response.data.menu_a);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            //Activar elementos del menú, según CF inicial al cargar o actualizar la página
            menu_inicial: function() {
                var indice_cf = app_cf.replace('/', '_');
                i = cf_indices[indice_cf][0];    //Índice del elemento en this.elementos
                this.elementos[i].activo = true;
                if ( this.elementos[i].submenu ) {
                    j = cf_indices[indice_cf][1];    //Índice del subelemento en this.elementos[i].subelementos
                    this.elementos[i].style = 'display: block;';
                    this.elementos[i].subelementos[j].activo = true;
                }
            }
        }
    });
</script>