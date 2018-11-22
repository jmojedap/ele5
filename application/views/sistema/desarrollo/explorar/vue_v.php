<script>
    new Vue({
        el: '#app_explorar',
        created: function(){
            this.obtener_listado();
        },
        data: {
            base_url: app_url,
            num_pagina: 1,
            max_pagina: 1,
            cant_resultados: 0,
            todos_seleccionados: false,
            seleccionados: [],
            usuarios: [],
            mostrar_filtros: false
        },
        methods: {
            obtener_listado: function (){
                axios.post(app_url + 'usuarios/listado/' + this.num_pagina, $('#form_busqueda').serialize())
                .then(response => {
                    this.usuarios = response.data.listado;
                    this.cant_resultados = response.data.cant_resultados;
                    this.max_pagina = response.data.max_pagina;
                    this.seleccionados = [];
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            buscar: function(){
                this.num_pagina = 1;
                this.obtener_listado();
            },
            pagina_siguiente: function(){
                this.num_pagina = Pcrn.limitar_entre(parseInt(this.num_pagina) + 1, 1, this.max_pagina);
                this.obtener_listado();

            },
            pagina_anterior: function(){
                this.num_pagina = Pcrn.limitar_entre(parseInt(this.num_pagina) - 1, 1, this.max_pagina);
                this.obtener_listado();
            },
            alternar_filtros: function(){
                this.mostrar_filtros = ! this.mostrar_filtros;
            },
            seleccionar_todos: function() {
                this.seleccionados = [];
                
                console.log(this.todos_seleccionados);

                if ( ! this.todos_seleccionados) {
                    for (usuario in this.usuarios) {
                        this.seleccionados.push(this.usuarios[usuario].id.toString());
                    }
                }
            },
            seleccionar: function () {
                this.todos_seleccionados = false;
            },
            eliminar_seleccionados: function () {
                axios.post(app_url + 'usuarios/eliminar_seleccionados/', $('#form_seleccionados').serialize())
                .then(response => {
                    for ( key_s in this.seleccionados ) {
                        for ( key_u in this.usuarios ) {
                            if ( this.seleccionados[key_s] == this.usuarios[key_u].id.toString() ) {
                                this.usuarios.splice(key_u, 1);
                            }
                        }
                    }
                    this.seleccionados = [];console.log(this.seleccionados[key_s]);
                })
                .catch(function (error) {
                     console.log(error);
                });
            }
        }
    });
</script>