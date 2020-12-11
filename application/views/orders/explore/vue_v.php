
<script>
// Variables
//-----------------------------------------------------------------------------
    var status_name = <?= json_encode($arr_status); ?>;
    var arr_niveles = <?= json_encode($arr_niveles); ?>;

// Filters
//-----------------------------------------------------------------------------

    Vue.filter('status_name', function (value) {
        if (!value) return '-';
        new_value = status_name[value];
        return new_value;
    });

    Vue.filter('currency', function (value) {
        if (!value) return '';
        value = '$ ' + new Intl.NumberFormat().format(value);
        return value;
    });

    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

    Vue.filter('nivel_name', function (value) {
        if (!value) return '';
        value = arr_niveles[value];
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
            cf: '<?= $cf; ?>',
            controller: '<?= $controller; ?>',
            num_page: 1,
            max_page: 1,
            list: <?= json_encode($list) ?>,
            element: [],
            selected: [],
            all_selected: false,
            filters: <?= json_encode($filters) ?>,
            str_filters: '<?= $str_filters ?>',
            showing_filters: false,
            search_num_rows: <?= $search_num_rows ?>,
            loading: false
        },
        methods: {
            get_list: function(){
                this.loading = true;
                axios.post(url_app + this.controller + '/get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows
                    this.str_filters = response.data.str_filters
                    $('#head_subtitle').html(response.data.search_num_rows);
                    history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                    this.loading = false;
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
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            },
            delete_selected: function(){
                var params = new FormData();
                params.append('selected', this.selected);
                
                axios.post(url_api + this.controller + '/delete_selected', params)
                .then(response => {
                    this.hide_deleted()
                    this.selected = []
                    toastr['info'](response.data.qty_deleted + ' registros eliminados')
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
        }
    });
</script>