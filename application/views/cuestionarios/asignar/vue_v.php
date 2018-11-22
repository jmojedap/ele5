<script>
    new Vue({
        el: '#app_asignar',
        created: function(){
            this.obtener_grupos();
        },
        data: {
            app_url: '<?php echo base_url() ?>',
            row_id: '<?php echo $row->id ?>',
            institucion_id: '<?php echo $busqueda['i'] ?>',
            grupo_id: '<?php echo $busqueda['g'] ?>',
            nivel: '<?php echo $busqueda['n'] ?>',
            todos_seleccionados: false,
            seleccionados: [],
            grupos: [],
            estudiantes: []
        },
        methods: {
            obtener_grupos: function () {
                axios.get(this.app_url + 'cuestionarios/lista_grupos/' + this.row_id + '/' + this.institucion_id + '/' + this.nivel)
                .then(response => {
                    this.grupos = response.data.lista;
                    if ( this.grupo_id == 0 ) { this.grupo_id = this.grupos[0].grupo_id; }
                    this.obtener_estudiantes();
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            obtener_estudiantes: function() {
                axios.get(this.app_url + 'cuestionarios/vista_estudiantes/' + this.row_id + '/' + this.grupo_id)
                .then(response => {
                    $('#lista_estudiantes').html(response.data.html);
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            grupo_actual: function(key) {
                this.grupo_id = this.grupos[key].grupo_id;
                this.obtener_estudiantes();
                console.log('hola grupo: ' + this.grupo_id);
            }
        }
    });
</script>