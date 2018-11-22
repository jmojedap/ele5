<script>
    new Vue({
        el: '#app_asignar',
        created: function(){
            this.obtener_lista();
        },
        data: {
            app_url: '<?php echo base_url() ?>',
            row_id: '<?php echo $row->id ?>',
            institucion_id: '<?php echo $institucion_id ?>',
            grupo_id: '0',
            grupo_key: 0,
            lista: [],
            lista_estudiantes: [],
            cant_grupos: 0,
            valores_form: {
                fecha_inicio: '<?php echo date('Y-m-d') ?>',
                fecha_fin: '<?php echo date('Y-m-d', strtotime('+7 days')) ?>',
                tiempo_minutos: '<?php echo $row->tiempo_minutos ?>',
                institucion_id: '<?php echo $institucion_id ?>'
            }
        },
        methods: {
            obtener_lista: function (){
                axios.get(this.app_url + 'cuestionarios/lista_grupos/' + this.row_id + '/' + this.institucion_id)
                .then(response => {
                    this.lista = response.data.lista;
                    if ( this.grupo_id == 0 ) { this.grupo_id = this.lista[0].grupo_id; }
                    this.cant_grupos = response.data.cant_grupos;
                    this.obtener_lista_estudiantes();
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            enviar_formulario: function() {
                axios.post(this.app_url + 'cuestionarios/asignar_e/' + this.row_id, $('#formulario').serialize())
                .then(response => {
                    console.log(response.data.ejecutado);
                    
                    if ( response.data.ejecutado == 1 ) 
                    {
                        this.grupo_id = parseInt(this.valores_form.grupo_id);
                        this.obtener_lista();
                        console.log('grupo_id: ' + this.grupo_id);
                        toastr['success']('Cambios guardados');                        
                    } else {
                        toastr['error']('Datos no guardados');
                    }
                    
                })
                .catch(function (error) {
                     console.log(error);
                });
                //$('#modal_formulario').modal('toggle');
            },
            //Establece un elemento como el actual
            elemento_actual: function(key) {
                this.grupo_key = key;
                this.grupo_id = this.lista[key].grupo_id;
                this.obtener_lista_estudiantes();
            },
            eliminar_elemento: function() {
                this.elemento_actual(this.grupo_key);
                var evento_id = this.lista[this.grupo_key].id;
                console.log(this.row_id + '/' + evento_id);
                axios.get(this.app_url + 'cuestionarios/eliminar_cg/' + this.row_id + '/' + evento_id)
                .then(response => {
                    console.log(response.data);
                    if ( response.data.ejecutado == 1 )
                    {
                        this.lista.splice(this.grupo_key, 1);
                        $('#lista_estudiantes').html('');
                        toastr['info']('Asignación de grupo eliminada');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            cargar_formulario: function(key) {
                this.elemento_actual(key);
                
                var row = this.lista[key]
                this.valores_form.grupo_id = '0' + row.grupo_id;
                this.valores_form.tiempo_minutos = row.tiempo_minutos;
                this.valores_form.fecha_inicio = row.fecha_inicio;
                this.valores_form.fecha_fin = row.fecha_fin;
                console.log(this.lista[key].grupo_id);
            },
            limpiar_formulario: function() {
                this.grupo_id = 0;
                this.valores_form.grupo_id = '';
            },
            obtener_lista_estudiantes: function() {
                axios.get(this.app_url + 'cuestionarios/vista_estudiantes/' + this.row_id + '/' + this.grupo_id)
                .then(response => {
                    $('#lista_estudiantes').html(response.data.html);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            reiniciar_uc: function() {
                console.log('REINICIAR UC');
            }
        }
    });
</script>