<script>
// Variables
//-----------------------------------------------------------------------------
    var form_values = {
        nombre: '',
        apellidos: '',
        username: '',
        rol_id: '',
        email: '',
        no_documento: '',
        tipo_documento_id: '01',
        sexo: '',
        notas: '',
        institucion_id: 0,
    };

// Vue App
//-----------------------------------------------------------------------------
    new Vue({
    el: '#app_add',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>',
            validation: {
                document_number_unique: -1,
                username_unique: -1,
                email_unique: -1,
                lower_role: -1
            },
            options_document_type: <?= json_encode($options_document_type) ?>,
            options_sexo: <?= json_encode($options_sexo) ?>,
            options_rol: <?= json_encode($options_rol) ?>,
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
                axios.post(url_app + 'usuarios/create/institucional', $('#add_form').serialize())
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
        }
    });
</script>