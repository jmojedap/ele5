<script>
// Filtros
//-----------------------------------------------------------------------------
    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

// App
//-----------------------------------------------------------------------------
    new Vue({
        el: '#anotaciones_app',
        created: function(){
            this.get_list();
        },
        data: {
            grupo_id: <?= $row->id ?>,
            flipbook_id: '<?= $flipbook_id ?>',
            tema_id: '0<?= $tema_id ?>',
            temas: <?= json_encode($temas->result()) ?>,
            anotaciones: [],
            avg_calificacion: 0
        },
        methods: {
            get_list: function(){
                console.log('actualizando');
                axios.get(url_api + 'flipbooks/get_anotaciones_grupo/' + this.flipbook_id + '/' + this.grupo_id + '/' + this.tema_id)
                .then(response => {
                    history.pushState(null, null, url_api + 'grupos/anotaciones/' + this.grupo_id + '/' + this.flipbook_id + '/' + this.tema_id);
                    this.anotaciones = response.data.list;
                    this.avg_calificacion = response.data.avg_calificacion;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            update_flipbook: function(){
                axios.get(url_api + 'flipbooks/get_temas/' + this.flipbook_id)
                .then(response => {
                    this.temas = response.data.list;
                    this.tema_id = '0';
                    this.get_list();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            set_calificacion: function(anotacion_key, calificacion){

                var meta_id = this.anotaciones[anotacion_key].id;
                console.log(meta_id);
                console.log(calificacion);

                let formData = new FormData;
                formData.append('calificacion', calificacion);

                this.anotaciones[anotacion_key].calificacion = calificacion;
                this.update_avg_calificacion();
                axios.post(url_api + 'flipbooks/calificar_anotacion/' + meta_id, formData)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        toastr['success']('Calificación guardada');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            update_avg_calificacion: function(){
                var sum = 0;
                var qty_calificaciones = 0;
                this.anotaciones.forEach(anotacion => {
                    console.log(anotacion.calificacion);
                    sum += parseInt(anotacion.calificacion);
                    if ( anotacion.calificacion > 0 ) qty_calificaciones += 1;  //Tiene calificacion
                });

                this.avg_calificacion = parseInt(sum / qty_calificaciones);
            },
            calificacion_color: function(calificacion){
                var calificacion_color = '';
                if ( calificacion > 0 ) calificacion_color = 'calificacion_rango_1_5';
                if ( calificacion > 20 ) calificacion_color = 'calificacion_rango_2_5';
                if ( calificacion > 40 ) calificacion_color = 'calificacion_rango_3_5';
                if ( calificacion > 60 ) calificacion_color = 'calificacion_rango_4_5';
                if ( calificacion > 80 ) calificacion_color = 'calificacion_rango_5_5';
                
                return calificacion_color;
            },
            star_class: function(calificacion, num){
                var star_class = 'far';
                if ( calificacion > 20 * (num - 1) ) star_class = 'fa';
                return star_class;
            },
            calificacion_name: function(calificacion){
                var calificacion_name = '';
                if ( calificacion > 0 ) calificacion_name = 'Muy baja';
                if ( calificacion > 20 ) calificacion_name = 'Baja';
                if ( calificacion > 40 ) calificacion_name = 'Media';
                if ( calificacion > 60 ) calificacion_name = 'Alta';
                if ( calificacion > 80 ) calificacion_name = 'Muy alta';
                
                return calificacion_name;
            }
        },
        computed: {
            link_export: function(){
                return url_app + 'grupos/exportar_anotaciones/' + this.grupo_id + '/' + this.flipbook_id + '/' + this.tema_id;
            },
        }
    });
</script>