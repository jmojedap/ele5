<script>
var kitsInstitucionesApp = new Vue({
    el: '#kitsInstitucionesApp',
    data: {
        kit: <?= json_encode($row) ?>,
        loading: false,
        filters: {
            q: ''
        },
        currInstitucion: {
            id: 0
        },
        institucionesBuscadas: [],
        instituciones: <?= json_encode($instituciones->result()) ?>,
        selected: [],
        allSelected: false,
        updatingAsignacionId: 0,
    },
    methods: {
        agregarInstitucion: function(institucionId) {
            axios.get(url_api + 'kits/agregar_institucion/' + this.kit.id + '/' + institucionId)
                .then(response => {
                    if (response.data.status == 1) {
                        toastr['success'](
                            'La institución se agregó al kit y se realizaron las asignaciones')
                        this.getInstituciones()
                    }
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        searchInstituciones: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('searchInstitucionForm'))
            axios.post(url_api + 'instituciones/get/', formValues)
                .then(response => {
                    this.institucionesBuscadas = response.data.list
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
        },
        //Actualizar listado de instituciones asignadas al kit
        getInstituciones: function() {
            this.loading = true
            console.log('Cargando instituciones')
            axios.get(url_api + 'kits/get_instituciones/' + this.kit.id)
                .then(response => {
                    this.instituciones = response.data.instituciones
                })
                .catch(function(error) {
                    console.log(error)
                })
            this.loading = false
        },
        asignar: function(asignacionId, depurar) {
            this.loading = true
            axios.get(url_api + 'kits/asignar/' + this.kit.id + '/' + asignacionId + '/' + depurar)
                .then(response => {
                    this.loading = false
                    if (response.data.status == 1) {
                        toastr['success']('Asignación actualizada')
                        this.getInstituciones()
                    } else {
                        toastr['error']('Ocurrió un error en el proceso')
                    }
                })
                .catch(function(error) {
                    toastr['error']('Ocurrió un error en el proceso')
                    this.loading = false
                    console.log(error)
                })
        },
        setCurrent: function(key) {
            this.currInstitucion = this.instituciones[key]
        },
        selectAll: function() {
            this.selected = [];
            if (!this.allSelected) {
                for (element in this.instituciones) {
                    this.selected.push(this.instituciones[element].asignacion_id);
                }
            }
        },
        delete_element: function() {
            axios.get(url_api + 'kits/quitar_institucion/' + this.kit.id + '/' + this.currInstitucion.asignacion_id)
                .then(response => {
                    if (response.data.qty_deleted >= 0) {
                        toastr['info']('Institución retirada del kit')
                        toastr['info']('Asignaciones eliminadas: ' + response.data.qty_deleted)
                        this.getInstituciones()
                    }
                })
                .catch(function(error) { console.log(error) })
        },
        //Determinar si una institución buscada está o no en las actuales instituciones
        //del kit
        inCurrentInstituciones: function(institucionIdBuscada){
            var inCurrentInstituciones = false
            const institucionBuscada = this.instituciones.find(institucion => institucion.id == institucionIdBuscada)
            if ( institucionBuscada != null ) inCurrentInstituciones = true
            return inCurrentInstituciones

        },
        runProcess: async function(depurar) {
            this.loading = true
            for (const asignacionId of this.selected) {
                this.updatingAsignacionId = asignacionId
                const response = await axios.get(url_api + 'kits/asignar/' + this.kit.id + '/' + asignacionId + '/' + depurar)
                console.log(response.data)
            }
            this.updatingAsignacionId = 0
            toastr['info']('Actualización de asignaciones ejecutada')
            this.getInstituciones()
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('YYYY-MMM-DD HH:mm')
        },
        ago: function(date) {
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
    }
})
</script>