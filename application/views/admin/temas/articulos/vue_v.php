<script>
var articulosApp = new Vue({
    el: '#articulosApp',
    created: function(){
        //this.get_list()
    },
    data: {
        temaId: <?= $row->id ?>,
        articulos: <?= json_encode($articulos->result()) ?>,
        currentArticulo: {id:0},
        section: 'list',
        loading: false,
        fields: {nombre_post: ''},
        arrStatus: <?= json_encode($arrStatus) ?>,
    },
    methods: {
        setCurrent: function(key){
            this.currentArticulo = this.articulos[key]
        },
        deleteElement: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('selected', this.currentArticulo.id)
            axios.post(URL_API + 'posts/delete_selected/', formValues)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    toastr['info']('Artículo eliminado')
                    this.currentArticulo.show = 0
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('addArticuloForm'))
            axios.post(URL_API + 'posts/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Articulo creado')
                    this.currentArticulo.id = response.data.saved_id
                    this.section = 'list'
                    this.getList()
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        statusName: function(value = '', field = 'name'){
            var statusName = ''
            var item = this.arrStatus.find(row => row.cod == value)
            if ( item != undefined ) statusName = item[field]
            return statusName
        },
        getList: function(){
            axios.get(URL_API + 'temas/get_articulos/' + this.temaId)
            .then(response => {
                this.articulos = response.data.list
            })
            .catch(function(error) { console.log(error) })
        },
    }
})
</script>