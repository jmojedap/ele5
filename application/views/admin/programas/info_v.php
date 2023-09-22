<div id="infoProgramaApp" class="container">
    Hola
</div>

<script>
var infoProgramaApp = new Vue({
    el: '#infoProgramaApp',
    created: function(){
        //this.get_list()
    },
    data: {
        row: <?= json_encode($row) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>