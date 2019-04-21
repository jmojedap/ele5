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
            pagina: [],
            data: {
                relacionados: {
                    1: {},
                    2: {},
                    3: {}
                }
            },
            anotaciones: {},
            anotacion: '',
            ver_indice: false,
            ver_formulario: false,
            ver_preguntas: false,
            preguntas: [
                'Pregunta seleccionada 1',
                'Pregunta seleccionada 2',
                'Pregunta seleccionada 3',
                'Pregunta seleccionada 4',
                'Pregunta seleccionada 5',
                'Pregunta seleccionada 6',
                'Pregunta seleccionada 7',
                'Pregunta seleccionada 8',
                'Pregunta seleccionada 9',
                'Pregunta seleccionada 10',
                'Pregunta seleccionada 11',
                'Pregunta seleccionada 12',
                'Pregunta seleccionada 13',
                'Pregunta seleccionada 14',
                'Pregunta seleccionada 15',
                'Pregunta seleccionada 16',
                'Pregunta seleccionada 17',
                'Pregunta seleccionada 18',
                'Pregunta seleccionada 19',
                'Pregunta seleccionada 20'
            ]
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
                $('#img_pagina').attr('src', '<?php echo URL_RECURSOS . 'imagenes/flipbook/pagina_cargando.png' ?>');
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
                var clase = 'btn-default';
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
            },
            alt_ver_preguntas: function(){
                this.ver_preguntas = !this.ver_preguntas;
                console.log(this.ver_preguntas);
            },
            seleccionar_pregunta: function(key_pregunta){
                console.log(key_pregunta);
                this.anotacion = this.preguntas[key_pregunta];
            }
        }
    });
</script>