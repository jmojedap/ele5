<script>
    var hora_max = new Date('<?php echo $row_uc->inicio_respuesta ?>');
    hora_max.setMinutes(hora_max.getMinutes() + <?php echo $row_uc->tiempo_minutos ?>);
    
    new Vue({
        el: '#resolver_cuestionario',
        created: function(){
            this.obtener_lista();
        },
        data: {
            app_url: '<?php echo base_url() ?>',
            uc_id: '<?php echo $row_uc->id ?>',
            usuario_id: '<?php echo $row_uc->usuario_id ?>',
            cuestionario_id: '<?php echo $row_uc->cuestionario_id ?>',
            pregunta_key: 0,
            lista: [],
            pregunta: [],
            letras: ['A', 'B', 'C', 'D'],
            clave_opciones: [0,1,2,3],
            opciones: [],
            rta_key: 0,
            respuestas: '',
            resultados: '',
            cant_preguntas: 0,
            cant_respondidas: 0,
            porcentaje: 0,
            hora_valida: true,
            hora_max: hora_max,
            finalizado: false,
            milisec: 0
        },
        methods: {
            obtener_lista: function (){
                axios.get(this.app_url + 'cuestionarios/lista_preguntas/' + this.cuestionario_id)
                .then(response => {
                    this.lista = response.data.lista;
                    this.cant_preguntas = response.data.cant_preguntas;
                    this.cargar_respuestas();
                    this.cargar_resultados();
                    this.pregunta = this.lista[0];
                    this.cargar_opciones();
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            cargar_opciones: function(){
                this.random_opciones();
                this.opciones = [];
                this.opciones.push(this.pregunta.opcion_1);
                this.opciones.push(this.pregunta.opcion_2);
                this.opciones.push(this.pregunta.opcion_3);
                this.opciones.push(this.pregunta.opcion_4);
            },
            //Desordena aleatoriamene this.clave_opciones
            random_opciones: function(){
                var i, j, temp;
                for (i = this.clave_opciones.length - 1; i > 0; i--) 
                {
                    j = Math.floor(Math.random() * (i + 1));
                    temp = this.clave_opciones[i];
                    this.clave_opciones[i] = this.clave_opciones[j];
                    this.clave_opciones[j] = temp;
                }
            },
            //Cargar respuestas ya guardadas en row_uc
            cargar_respuestas: function() {
                this.respuestas = '<?php echo $row_uc->respuestas ?>';
                arr_respuestas = this.respuestas.split('-');
                for ( i in arr_respuestas ) {
                    this.lista[i].rta = arr_respuestas[i];
                    console.log(arr_respuestas[i]);
                }
                this.act_totales();
            },
            //Cargar respuestas ya guardadas en row_uc
            cargar_resultados: function() {
                this.resultados = '<?php echo $row_uc->resultados ?>';
                arr_resultados = this.resultados.split('-');
                for ( i in arr_resultados ) {
                    this.lista[i].res = arr_resultados[i];
                    console.log(arr_resultados[i]);
                }
                this.act_totales();
            },
            seleccionar_pregunta: function(key) {
                this.pregunta_key = key;
                this.pregunta = this.lista[this.pregunta_key];
                this.cargar_opciones();
            },
            siguiente_pregunta: function() {
                var key = Pcrn.ciclo_entre(this.pregunta_key + 1, 0, this.cant_preguntas - 1);
                this.seleccionar_pregunta(key);
            },
            anterior_pregunta: function() {
                var key = Pcrn.ciclo_entre(this.pregunta_key - 1, 0, this.cant_preguntas - 1);
                this.seleccionar_pregunta(key);
            },
            responder: function(opcion_key) {
                this.rta_key = opcion_key;
                var rta = opcion_key + 1;
                if ( this.pregunta.clv == rta ){
                    this.lista[this.pregunta_key].res = 1;
                } else {
                    this.lista[this.pregunta_key].res = 0;
                }
                this.lista[this.pregunta_key].rta = opcion_key + 1;
                this.act_totales();
                this.guardar_uc_prob();
                
                //DESACTIVADO 2018-09-17
                this.validar_hora();
                
                /*if ( this.hora_valida == true )
                {
                    this.guardar_uc_prob();
                } else {
                    this.guardar_finalizar();
                }*/
                
            },
            borrar_respuesta: function() {
                this.lista[this.pregunta_key].rta = 0;
                this.act_totales();
            },
            act_totales: function() {
                this.act_respondidas();
                this.act_respuestas();
                this.act_resultados();
            },
            //Cantidad de preguntas respondidas
            act_respondidas: function() {
                this.cant_respondidas = 0;
                for ( i in this.lista ) 
                {
                    if ( this.lista[i].rta > 0 ) { this.cant_respondidas++;} 
                }
                this.porcentaje = Math.floor(100 * this.cant_respondidas / this.cant_preguntas);
                console.log('Porcentaje: ' + this.porcentaje);
            },
            //Str respuestas
            act_respuestas: function() {
                var arr_respuestas = this.lista.map(a => a.rta);
                this.respuestas = arr_respuestas.join('-');
            },
            //Str resultados
            act_resultados: function() {
                var arr_resultados = this.lista.map(a => a.res);
                this.resultados = arr_resultados.join('-');
            },
            //Guardar respuestas en DB de forma probabilística
            guardar_uc_prob: function() {
                //this.validar_hora();
                var probabilidad = Math.random();
                //Guardado probabilístico (20% de las veces, aprox. cada 5 preguntas)
                if ( probabilidad < 0.2 )
                {
                    console.log('Guardado DB: ' + probabilidad);
                    this.guardar_uc();
                } else {
                    console.log('Guardado LOCAL: ' + probabilidad);
                }
            },
            //Guardar respuestas en usuario_cuestionario (row_uc)
            guardar_uc: function() {
                var params = new FormData();
                params.append('respuestas', this.respuestas);
                params.append('resultados', this.resultados);
                params.append('cant_respondidas', this.cant_respondidas);
                
                axios.post(this.app_url + 'cuestionarios/guardar_uc/' + this.uc_id, params)
                .then(response => {
                    console.log(response.data.message);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            //Validar si la hora actual es anterior a la hora límite de respuesta
            validar_hora: function() {
                var hora = new Date();  //Hora actual
                var diferencia = hora_max.getTime() - hora.getTime();
                console.log("Milisegundos restantes: " + diferencia);
                this.milisec = diferencia;
                if ( hora > this.hora_max ) { this.hora_valida = false; } else { console.log('HORA VÁLIDA'); }
            },
            //Guardar respuestas, y luego finalizar
            guardar_finalizar: function() {
                //Guardar UC
                    //const params = new URLSearchParams();
                    var params = new FormData();
                    params.append('respuestas', this.respuestas);
                    params.append('resultados', this.resultados);
                    params.append('cant_respondidas', this.cant_respondidas);

                    axios.post(this.app_url + 'cuestionarios/guardar_uc/' + this.uc_id, params)
                    .then(response => {
                        if ( response.data.status == 1 ) { this.finalizar(); }
                        if ( response.data.status == 0 ) {
                            toastr['error'](response.data.message);
                        }
                    })
                    .catch(function (error) {
                         console.log(error);
                    });
            },
            //Finaliza cuestionario y redirige a resultados
            finalizar: function() {
                this.finalizado = true;
                axios.get(this.app_url + 'cuestionarios/n_finalizar/' + this.uc_id)
                .then(response => {
                    console.log(response.data.mensaje);
                    if ( response.data.cant_respuestas == this.cant_preguntas )
                    {
                        window.location = this.app_url + 'usuarios/resultados_detalle/' + this.usuario_id + '/' + this.uc_id;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>