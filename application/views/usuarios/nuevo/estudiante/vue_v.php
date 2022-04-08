<script>
// Variables
//-----------------------------------------------------------------------------
    var form_values = {
        nombre: '',
        apellidos: '',
        username: '',
        email: '',
        no_documento: '',
        tipo_documento_id: '01',
        sexo: '',
        notas: '',
        institucion_id: 0,
        grupo_id: 0,
    };

// Vue App
//-----------------------------------------------------------------------------
    new Vue({
    el: '#app_add',
        data: {
            instituciones_q: '',
            instituciones: [],
            institucion: {id:0, nombre_institucion: ''},
            grupos: [],
            grupo: {},
            form_values: form_values,
            row_id: '<?= $row->id ?>',
            validation: {
                document_number_unique: -1,
                username_unique: -1,
                email_unique: -1
            },
            options_document_type: <?= json_encode($options_document_type) ?>,
            options_sexo: <?= json_encode($options_sexo) ?>,
            loading: false
        },
        methods: {
            validate_form: function() {
                axios.post(url_app + 'usuarios/validate/' + this.row_id, $('#add_form').serialize())
                .then(response => {
                    this.validation = response.data.validation
                })
                .catch(function (error) { console.log(error) })
            },
            validate_send: function () {
                axios.post(url_app + 'usuarios/validate/' + this.row_id, $('#add_form').serialize())
                .then(response => {
                    if (response.data.status == 1) {
                        this.send_form()
                    } else {
                        toastr['error']('Hay casillas incompletas o incorrectas')
                        this.loading = false
                    }
                })
                .catch(function (error) { console.log(error) })
            },
            send_form: function() {
                this.loading = true
                axios.post(url_app + 'usuarios/create/', $('#add_form').serialize())
                .then(response => {
                    if (response.data.saved_id > 0) {
                        toastr['success']('Guardado')
                        toastr['info']('Redirigiendo...')
                        setTimeout(() => {
                            window.location = url_app + 'usuarios/explorar/1/?q=' + form_values.username
                        }, 3000);
                    } else {
                        this.loading = false
                    }
                })
                .catch(function (error) { console.log(error) })
            },
            generate_username: function() {
                const params = new URLSearchParams();
                params.append('first_name', this.form_values.first_name)
                params.append('last_name', this.form_values.last_name)
                
                axios.post(url_app + 'users/username/', params)
                .then(response => {
                    this.form_values.username = response.data
                })
                .catch(function (error) { console.log(error) })
            },
            get_instituciones: function(){
                console.log('buscando instituciones...')
                this.loading = true
                var form_data = new FormData()
                form_data.append('q', this.instituciones_q)
                axios.post(url_api + 'instituciones/get/', form_data)
                .then(response => {
                    console.log('institucion_id:')
                    console.log(form_values.institucion_id)
                    console.log('grupo_id:')
                    console.log(form_values.grupo_id)
                    this.instituciones = response.data.list
                    this.loading = false
                })
                .catch( function(error) {console.log(error)} )
            },
            set_institucion: function(key){
                this.institucion = this.instituciones[key]
                this.form_values.institucion_id = this.instituciones[key].id
                this.get_grupos()
            },
            unset_institucion: function(){
                this.institucion = {}
                this.form_values.institucion_id = 0
                this.grupo = {}
                this.form_values.grupo_id = 0
            },
            get_grupos: function(){
                this.loading = true
                var form_data = new FormData()
                form_data.append('i', this.form_values.institucion_id)
                axios.post(url_api + 'grupos/get/', form_data)
                .then(response => {
                    this.grupos = response.data.list
                    this.loading = false
                })
                .catch( function(error) {console.log(error)} )
            },
            set_grupo: function(key){
                this.grupo = this.grupos[key]
                this.form_values.grupo_id = this.grupos[key].id
            },
            unset_grupo: function(){
                this.grupo = {}
                this.form_values.grupo_id = 0
            },
        }
    });
</script>