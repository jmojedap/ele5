<?php
    $unidades = [];
    for ($i=0; $i < $row->cantidad_unidades; $i++) { 
        $unidades[] = $i + 1;
    }
?>

<script>
var maxOrder = <?= $temas->num_rows() ?>;

var temasApp = new Vue({
    el: '#temasApp',
    created: function(){
        //this.get_list()
    },
    data: {
        programaId: <?= $row->id ?>,
        programa: <?= json_encode($row) ?>,
        temas: <?= json_encode($temas->result()) ?>,
        currentTema: {
            id:0
        },
        unidades: <?= json_encode($unidades) ?>,
        currentUnidad: -1,
        loading: false,
        arrArea: <?= json_encode($arrArea) ?>,
        results: [],
        currentOrder: maxOrder,
        filters: {
            q:''
        },
    },
    methods: {
        getList: function(){
            axios.get(URL_API + 'programas/get_temas/' + this.programaId)
            .then(response => {
                this.temas = response.data.list
            })
            .catch(function(error) { console.log(error) })
        },
        moverTema: function(temaId, nuevaPosicion){
            this.setCurrentById(temaId)
            axios.get(URL_API + 'programas/mover_tema/' + this.programaId + '/' + temaId + '/' + nuevaPosicion)
            .then(response => {
                if (response.data.qty_affected > 0) {
                    toastr['info']('Tema movida')
                    this.temas = response.data.list
                }
            })
            .catch(function(error) { console.log(error) })
        },
        setCurrent: function(key){
            this.currentTema = this.temas[key]
        },
        setCurrentById: function(temaId){
            this.currentTema = this.temas.find(item => item.id == temaId )
        },
        deleteElement: function(){
            axios.get(URL_API + 'programas/remove_tema/'+ this.programaId + '/' + this.currentTema.id + '/' + this.currentTema.pt_id)
            .then(response => {
                if (response.data.qty_deleted > 0) {
                    toastr['info']('Tema retirado del programa')
                    this.temas = response.data.list
                }
            })
            .catch(function(error) { console.log(error) })
        },
        areaName: function(value = '', field = 'name'){
            var areaName = ''
            var item = this.arrArea.find(row => row.id == value)
            if ( item != undefined ) areaName = item[field]
            return areaName
        },
        searchTemas: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('search-temas-form'))
            axios.post(URL_API + 'temas/get/', formValues)
            .then(response => {
                this.results = response.data.list
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        saveProgramaTema: function(temaId){
            this.loading = true
            var formValues = new FormData()
            formValues.append('programa_id', this.programaId)
            formValues.append('tema_id', temaId)
            formValues.append('unidad', this.currentUnidad < 1 ? 1 : this.currentUnidad)
            formValues.append('orden', this.temas.length)
            axios.post(URL_API + 'programas/save_programa_tema/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                    this.getList()
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>