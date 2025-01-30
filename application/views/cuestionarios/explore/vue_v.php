<?php
    $arr_etiquetas_areas = array();
    foreach ($arr_areas as $area_id => $area_name) {
        $arr_etiquetas_areas[$area_id] = $this->App_model->etiqueta_area($area_id);
    }
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var arr_niveles = <?= json_encode($arr_niveles); ?>;
    var arr_areas = <?= json_encode($arr_etiquetas_areas); ?>;
    var arr_tipos = <?= json_encode($arr_tipos); ?>;

// Filters
//-----------------------------------------------------------------------------
    Vue.filter('area_name', function (value) {
        if (!value) return '';
        value = arr_areas[value];
        return value;
    });

    Vue.filter('nivel_name', function (value) {
        if (!value) return '';
        value = arr_niveles[value];
        return value;
    });

    Vue.filter('tipo_name', function (value) {
        if (!value) return '';
        value = arr_tipos[value];
        return value;
    });

    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });
// App
//-----------------------------------------------------------------------------
    new Vue({
        el: '#app_explore',
        created: function(){
            //this.get_list();
        },
        data: {
            cf: '<?= $cf; ?>',
            controller: '<?= $controller; ?>',
            num_page: 1,
            max_page: <?= $max_page; ?>,
            search_num_rows: <?= $search_num_rows ?>,
            list: <?= json_encode($list) ?>,
            element: [],
            selected: [],
            all_selected: false,
            filters: <?= json_encode($filters) ?>,
            showing_filters: false,
            str_filters: '<?= $str_filters ?>',
            delete_confirm: false,   //Casilla para confirmar eliminación masiva
            delete_process: false
        },
        methods: {
            get_list: function(){
                $('.table-responsive').hide()
                axios.post(URL_API + this.controller + '/get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    this.str_filters = response.data.str_filters;
                    this.filters = response.data.filters;
                    $('#head_subtitle').html(response.data.search_num_rows);
                    history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                    $('.table-responsive').show()
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            select_all: function() {
                this.selected = [];
                if (this.all_selected) {
                    for (element in this.list) {
                        this.selected.push(this.list[element].id);
                    }
                }
            },
            sum_page: function(sum){
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            },
            delete_selected: function(){
                var params = new FormData();
                params.append('selected', this.selected);
                
                axios.post(URL_API + this.controller + '/delete_selected', params)
                .then(response => {
                    this.hide_deleted();
                    this.selected = [];
                    if ( response.data.status == 1 )
                    {
                        toastr_cl = 'info';
                        toastr_text = 'Registros eliminados';
                        toastr[toastr_cl](toastr_text);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            hide_deleted: function(){
                for (let index = 0; index < this.selected.length; index++) {
                    const element = this.selected[index];
                    console.log('ocultando: row_' + element);
                    $('#row_' + element).addClass('table-danger');
                    $('#row_' + element).hide('slow');
                }
            },
            set_current: function(key){
                this.element = this.list[key];
            },
            toggle_filters: function(){
                this.showing_filters = !this.showing_filters;
                $('#adv_filters').toggle('fast');
            },
            area_label: function(area_id){
                return arr_areas[area_id];
            },
            delete_filtered: function(){
                $('.table-responsive').hide()
                this.delete_process = true;
                axios.get(URL_API + this.controller + '/delete_filtered/' + this.search_num_rows + '/?' + this.str_filters)
                .then(response => {
                    $('#delete_filtered_modal').modal('hide')
                    this.delete_process = false;
                    console.log(response.data);
                    toastr['info']("Cuestionarios eliminados: " + response.data.qty_deleted)
                    setTimeout(() => {
                        window.location = url_app + 'cuestionarios/explorar/?' + this.str_filters;
                    }, 3000);
                })
                .catch(function (error) {
                    console.log(error);
                    window.location = url_app + 'cuestionarios/explorar/?' + this.str_filters;
                });
            },
        }
    });
</script>