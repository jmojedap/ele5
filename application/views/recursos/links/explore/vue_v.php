
<script>
// Variables
//-----------------------------------------------------------------------------
    var arr_areas = <?php echo json_encode($arr_areas); ?>;
    var arr_tipos = <?php echo json_encode($arr_tipos); ?>;
    var arr_componentes = <?php echo json_encode($arr_componentes); ?>;

// Filtros
//-----------------------------------------------------------------------------

    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

    Vue.filter('area_name', function (value) {
        if (!value) return '';
        value = arr_areas[value];
        return value;
    });

    Vue.filter('tipo_name', function (value) {
        if (!value) return '';
        value = arr_tipos[value];
        return value;
    });

    Vue.filter('componente_name', function (value) {
        if (!value) return '';
        value = arr_componentes[value];
        return value;
    });
// App
//-----------------------------------------------------------------------------

    new Vue({
        el: '#app_explore',
        created: function(){
            //this.get_list();
        },
        data: {
            cf: '<?php echo $cf; ?>',
            controller: '<?php echo $controller; ?>',
            num_page: 1,
            max_page: <?php echo $max_page ?>,
            list: <?php echo json_encode($list) ?>,
            element: [],
            selected: [],
            all_selected: false,
            filters: <?php echo json_encode($filters) ?>,
            showing_filters: false,
            group_id: 0
        },
        methods: {
            get_list: function(){
                this.num_page = 1;
                axios.post(app_url + this.controller + '/links_get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    $('#head_subtitle').html(response.data.search_num_rows);
                    history.pushState(null, null, app_url + this.cf + this.num_page + '/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            select_all: function() {
                this.selected = [];
                if (!this.all_selected) {
                    for (element in this.list) {
                        this.selected.push(this.list[element].id);
                    }
                }
            },
            sum_page: function(sum){
                this.num_page = Pcrn.limit_between(+this.num_page + +sum, 1, this.max_page);
                axios.post(app_url + this.controller + '/links_get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    history.pushState(null, null, app_url + this.cf + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            delete_selected: function(){
                var params = new FormData();
                params.append('selected', this.selected);
                
                axios.post(app_url + this.controller + '/delete_selected', params)
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
            //Especiales
            //Programación de link a grupo en calendario 
            send_schedule_form: function(){
                axios.post(app_url + 'recursos/links_programar/', $('#schedule_form').serialize())
                .then(response => {
                    console.log(response.data);
                    if ( response.data.saved_id > 0) {
                        toastr['success']('Link asignado al grupo');
                        $('#btn_calendar').show('slow');
                    } else {
                        toastr['error']('No se asignó el link');
                    }
                    console.log('tp=05&g=' + this.group_id);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>