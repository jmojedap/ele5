<script>
// Variables
//-----------------------------------------------------------------------------
    var arr_areas = <?php echo json_encode($arr_areas); ?>;

// Filtros
//-----------------------------------------------------------------------------
    Vue.filter('area_name', function (value) {
        if (!value) return '';
        value = arr_areas[value];
        return value;
    });

    Vue.filter('difficulty_class', function (value) {
        if (!value) return '';
        new_value = 'bg-success';
        if ( value > 20 ) { new_value = 'bg-info'; }
        if ( value > 40 ) { new_value = 'bg-warning'; }
        if ( value > 60 ) { new_value = 'bg-danger'; }
        return new_value;
    });

    Vue.filter('difficulty_name', function (value) {
        if (!value) return '';
        new_value = 'Baja';
        if ( value > 20 ) { new_value = 'Media'; }
        if ( value > 40 ) { new_value = 'Alta'; }
        if ( value > 60 ) { new_value = 'Muy alta'; }
        return new_value;
    });

// VueApp
//-----------------------------------------------------------------------------
    new Vue({
        el: '#app_selectorp',
        data: {
            list: <?php echo json_encode($preguntas->result()) ?>,
            row_id: 0,
            row_key: 0,
            avg_difficulty: <?php echo $avg_difficulty ?>,
            show_detail: false
        },
        methods: {
            delete_element: function(row_key){
                this.row_key = row_key;
                this.row_id = this.list[this.row_key].id;
                console.log('eliminado' + this.row_id);

                axios.get(app_url + 'preguntas/selectorp_remove/' + this.row_id)
                .then(response => {
                    console.log(response.data.status)
                    this.list = response.data.preguntas;
                    this.avg_difficulty = response.data.avg_difficulty;
                    toastr['info']('Pregunta retirada');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>