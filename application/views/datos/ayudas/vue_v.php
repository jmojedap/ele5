
<script>
// App
//-----------------------------------------------------------------------------

    new Vue({
        el: '#app_explore',
        created: function(){
            this.get_list();
        },
        data: {
            cf: '<?php echo $cf; ?>',
            controller: '<?php echo $controller; ?>',
            num_page: 1,
            max_page: 1,
            list: [],
            element: [],
            selected: [],
            all_selected: false,
            filters: [],
            showing_filters: false
        },
        methods: {
            get_list: function(){
                axios.post(url_api + 'datos/ayudas_get/' + this.num_page, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    $('#head_subtitle').html(response.data.search_num_rows);
                    history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            sum_page: function(sum){
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            },
            hide_deleted: function(){
                for (let index = 0; index < this.selected.length; index++) {
                    const element = this.selected[index];
                    console.log('ocultando: row_' + element);
                    $('#row_' + element).addClass('table-danger');
                    $('#row_' + element).hide('slow');
                }
            },
            toggle_filters: function(){
                this.showing_filters = !this.showing_filters;
                $('#adv_filters').toggle('fast');
            },
        }
    });
</script>