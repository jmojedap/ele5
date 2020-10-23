<script>
    new Vue({
        el: '#flipbook',
        created: function(){
            this.cargar_data();
            this.cargar_pa_asignadas();
        },
        data: {
            app_url: '<?php echo base_url() ?>',
            carpeta_uploads: '<?php echo $carpeta_uploads ?>',
            num_pagina: <?php echo $num_pagina ?>,
            max_num_pag: <?php echo $row->num_paginas - 1 ?>,
            bookmark: '<?php echo $bookmark ?>',
            flipbook_id: '<?php echo $row->id ?>',
            pagina: {
                archivo_imagen: 'pf_nd_3.png',
                tema_id: 0
            },
            data: {
                relacionados: {
                    1: {},
                    2: {},
                    3: {}
                }
            },
            anotaciones: {},
            anotacion: {anotacion: '', calificacion: 0},
            ver_indice: false,
            grupo_id: <?php echo $this->session->userdata('grupo_id'); ?>,
            area_id: <?php echo $row->area_id ?>,
            nivel: <?php echo $row->nivel ?>,
            pregunta_id: 0,
            pa_asignadas: [],
            pregunta_personalizada: true,
            tiene_lectura: false,
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
                    var tema_id = this.data.anotaciones[key].tema_id;
                    this.anotaciones[tema_id] = this.data.anotaciones[key];
                }
                this.establecer_anotacion();
            },
            establecer_anotacion: function(){
                //console.log('tema:', this.pagina.tema_id);
                this.anotacion = {anotacion: '', calificacion: 0};
                if ( this.anotaciones[this.pagina.tema_id] != undefined ) {
                    this.anotacion = this.anotaciones[this.pagina.tema_id];
                    this.anotacion.calificacion = parseInt(this.anotacion.calificacion);
                }
            },
            cambiar_pagina: function() {
                $('#img_pagina').attr('src', '<?php echo URL_IMG . 'flipbook/pagina_cargando.png' ?>');
                this.pagina = this.data.paginas[this.num_pagina];
                this.establecer_anotacion();
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
                params.append('anotacion', this.anotacion.anotacion);
                
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
                $('#menu_recursos').toggleClass('d-none');
                $('#menu_recursos').toggleClass('d-lg-block');
            },
            cargar_pa_asignadas: function(){
                axios.get(this.app_url + 'grupos/pa_asignadas/' + this.grupo_id + '/' + this.area_id, )
                .then(response => {
                    this.pa_asignadas = response.data.pa_asignadas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            seleccionar_pregunta: function(pregunta_id){
                this.pregunta_id = pregunta_id;
            },
            //Verificar el estado de las variables y el tipo de asignación
            enviar_form_pa: function(){
                var enviar_form = false;

                if ( this.pregunta_personalizada ) {
                    this.pregunta_id = 0 
                    if ( $('#field-texto_pregunta').val() )
                    {
                        enviar_form = true
                    } else {
                        //No hay texto escrito, se marca como no válido
                        $('#field-texto_pregunta').addClass('is-invalid');
                    }
                } else {
                    if ( this.pregunta_id > 0 ){ 
                        enviar_form = true; //Hay pregunta seleccionada
                    } else {
                        toastr['info']('Debe seleccionar una de las preguntas');
                    }
                }

                if ( enviar_form ) { this.asignar_pa(); }
            },
            //Asignar pregunta abierta existente
            asignar_pa: function(){
                axios.post(this.app_url + 'grupos/asignar_pa/' + this.grupo_id + '/' + this.pregunta_id, $('#pa_form').serialize())
                .then(response => {
                    console.log(response.data.message)
                    if ( response.data.status ) {
                        toastr['success']('La pregunta fue asignada al grupo');
                        $('#modal_pa').modal('hide');
                        $('#field-texto_pregunta').val('');    //Limpiar campo
                        this.cargar_pa_asignadas();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Alterna valor de variable, pregunta existente o pregunta personalizada
            alternar_pregunta_personalizada: function(){
                this.pregunta_personalizada = ! this.pregunta_personalizada;  
            },
            cargar_lectura: function(){  
                axios.get(this.app_url + 'temas/lectura_dinamica_tema/' + this.pagina.tema_id)
                .then(response => {
                    console.log(response.data.message)
                    $('#lectura_modal_contenido').html(response.data.html);
                })
                .catch(function (error) {
                    console.log(error);
                });
                //$('#lectura_modal_contenido').html('Tema: ' + this.pagina.tema_id);
            },
            star_class: function(calificacion, num){
                var star_class = 'far';
                if ( calificacion > 20 * (num - 1) ) star_class = 'fa';
                return star_class;
            },
        }
    });
</script>