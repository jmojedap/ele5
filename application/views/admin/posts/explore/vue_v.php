
<script>
// Vue App
//-----------------------------------------------------------------------------
var appExplore = new Vue({
    el: '#appExplore',
    created: function(){
        this.calculateShowFilters()
    },
    data: {
        cf: '<?= $cf ?>',
        controller: '<?= $controller ?>',
        qtyResults: <?= $qtyResults ?>,
        perPage: 10,
        numPage: <?= $numPage ?>,
        maxPage: <?= $maxPage ?>,
        list: <?= json_encode($list) ?>,
        element: [],
        selected: [],
        allSelected: false,
        filters: <?= json_encode($filters) ?>,
        strFilters: '<?= $strFilters ?>',
        showFilters: false,
        loading: false,
        activeFilters: false,
        arrType: <?= json_encode($arrType) ?>,
    },
    methods: {
        getList: function(e, numPage = 1){
            this.loading = true
            var formValues = new FormData(document.getElementById('searchForm'))
            axios.post(URL_API + this.controller + '/get/' + numPage + '/' + this.perPage, formValues)
            .then(response => {
                this.numPage = numPage
                this.list = response.data.list
                this.maxPage = response.data.maxPage
                this.qtyResults = response.data.qtyResults
                this.strFilters = response.data.strFilters
                history.pushState(null, null, URL_APP + this.cf + this.numPage + '/?' + response.data.strFilters)
                this.allSelected = false
                this.selected = []
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        selectAll: function() {
            if ( this.allSelected )
            { this.selected = this.list.map(function(element){ return element.id }) }
            else
            { this.selected = [] }
        },
        sumPage: function(sum){
            var newNumPage = Pcrn.limit_between(this.numPage + sum, 1, this.maxPage)
            this.getList(null, newNumPage)
        },
        deleteSelected: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('selected', this.selected)
            axios.post(URL_API + this.controller + '/delete_selected', formValues)
            .then(response => {
                this.hideDeleted()
                this.selected = []
                if ( response.data.qty_deleted > 0 )
                {
                    toastr['info']('Registros eliminados: ' + response.data.qty_deleted)
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        hideDeleted: function(){
            this.selected.forEach(rowId => {
                $('#row_' + rowId).addClass('table-danger')
                $('#row_' + rowId).hide('slow')
            })
        },
        setCurrent: function(key){
            this.element = this.list[key]
        },
        toggleFilters: function(){
            this.showFilters = !this.showFilters
        },
        clearFilters: function(){
            Object.keys(this.filters).forEach(key => {
                this.filters[key] = ''
            })
            this.showFilters = false
            setTimeout(() => { this.getList() }, 100)
        },
        calculateShowFilters: function(){
            if ( this.strFilters.length > 0 ) this.showFilters = true
        },
        typeName: function(value = '', field = 'name'){
            var typeName = ''
            var item = this.arrType.find(row => row.cod == value)
            if ( item != undefined ) typeName = item[field]
            return typeName
        },
    }
})
</script>