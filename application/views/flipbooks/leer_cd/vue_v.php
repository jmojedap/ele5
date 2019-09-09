<script>
    new Vue({
        el: '#flipbook',
        created: function(){
            this.cargar_data();
        },
        data: {
            app_url: '<?php echo base_url() ?>',
            carpeta_uploads: '<?php echo $carpeta_uploads ?>',
            num_pagina: <?php echo $num_pagina ?>,
            max_num_pag: <?php echo $row->num_paginas - 1 ?>,
            bookmark: '<?php echo $bookmark ?>',
            flipbook_id: '<?php echo $row->id ?>',
            pagina: {
                archivo_imagen: '0db5031cfb53fe849a8d45978e22da7d.jpg'
            },
            data: {
                relacionados: {
                    1: {},
                    2: {},
                    3: {}
                }
            },
            anotaciones: {},
            anotacion: '',
            ver_indice: false
        },
        methods: {
            cargar_data: function () {
                axios.get(this.app_url + 'flipbooks/data/' + this.flipbook_id)
                .then(response => {
                    this.data = response.data;
                    //this.num_pagina = this.bookmark;
                    this.cambiar_pagina();
                    this.obtener_anotaciones();
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            obtener_anotaciones: function() {
                axios.get(this.app_url + 'flipbooks/json_anotaciones/' + this.flipbook_id)
                .then(response => {
                    this.data.anotaciones = response.data.anotaciones;
                    this.cargar_anotaciones();
                    console.log(response.data.row.id);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            cargar_anotaciones: function() {
                for (key in this.data.anotaciones)
                {
                    var num_pagina = this.data.anotaciones[key].num_pagina;
                    this.anotaciones[num_pagina] = this.data.anotaciones[key].anotacion;
                }
                this.anotacion = this.anotaciones[this.num_pagina];
            },  
            cambiar_pagina: function() {
                $('#img_pagina').attr('src', '<?php echo URL_IMG . 'flipbook/pagina_cargando.png' ?>');
                this.pagina = this.data.paginas[this.num_pagina];
                this.anotacion = this.anotaciones[this.num_pagina];
            },
            pagina_sig: function() {
                this.num_pagina = parseInt(this.num_pagina) + 1;
                this.num_pagina = Pcrn.ciclo_entre(this.num_pagina, 0, this.max_num_pag);
                console.log(this.num_pagina);
                this.cambiar_pagina();
            },
            pagina_ant: function() {
                this.num_pagina = parseInt(this.num_pagina) - 1;
                this.num_pagina = Pcrn.ciclo_entre(this.num_pagina, 0, this.max_num_pag);
                console.log(this.num_pagina);
                this.cambiar_pagina();
            },
            ir_a_pagina: function(num_pagina) {
                this.ver_indice = false;
                this.num_pagina = num_pagina;
                console.log(this.num_pagina);
                this.cambiar_pagina();
            },
            //Establecer bookmark, número de página actual, para el usuario en sesión
            establecer_bookmark: function() {
                axios.get(this.app_url + 'flipbooks/guardar_bookmark/' + this.flipbook_id + '/' + this.num_pagina)
                .then(response => {
                    this.bookmark = this.num_pagina
                    console.log(response.data);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            guardar_anotacion: function() {
                var params = new FormData();
                params.append('num_pagina', this.num_pagina);
                params.append('anotacion', this.anotacion);
                
                axios.post(this.app_url + 'flipbooks/guardar_anotacion/' + this.flipbook_id, params)
                .then(response => {
                    toastr["success"]('Anotación guardada');
                    this.anotaciones[this.num_pagina] = this.anotacion;
                    console.log('actualizando anotaciones: ' + this.anotacion);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clase_bookmark: function() {
                var clase = 'btn-light';
                if ( this.num_pagina == this.bookmark ) {
                    clase = 'btn-success';
                }
                return clase;
            },
            alternar_indice: function() {
                this.ver_indice = !this.ver_indice;
            },
            alternar_menu_recursos: function() {
                $('#alternar_menu_recursos').toggleClass('btn-default');
                $('#alternar_menu_recursos').toggleClass('btn-info');
                $('#menu_recursos').toggleClass('hidden-xs');
                $('#menu_recursos').toggleClass('hidden-sm');
            }
        }
    });
</script>