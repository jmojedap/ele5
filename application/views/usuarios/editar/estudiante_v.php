<div id="app_edit">
    <div class="center_box_750">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="validate_send">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">
                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-right">Nombre <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input
                                    name="nombre" type="text" class="form-control"
                                    required
                                    title="Nombre" placeholder="Nombre"
                                    v-model="form_values.nombre"
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="apellidos" class="col-md-4 col-form-label text-right">Apellidos <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input
                                    name="apellidos" type="text" class="form-control"
                                    required
                                    title="Apellidos" placeholder="Apellidos"
                                    v-model="form_values.apellidos"
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-right">Username <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input
                                    id="field-username" name="username" class="form-control"
                                    placeholder="username" title="Puede contener letras y números, entre 6 y 25 caractéres, no debe contener espacios ni caracteres especiales"
                                    required pattern="^[a-zA-Z0-9-_\.]{6,25}$"
                                    v-bind:class="{ 'is-invalid': validation.username_unique == 0, 'is-valid': validation.username_unique == 1 }"
                                    v-model="form_values.username"
                                    v-on:change="validate_form"
                                    >
                                    <span class="invalid-feedback">
                                        El username escrito no está disponible, por favor elija otro
                                    </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="grupo_id" class="col-md-4 col-form-label text-right">Grupo <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="grupo_id" v-model="form_values.grupo_id" class="form-control" required>
                                    <option v-for="(option_grupo, key_grupo) in options_grupo" v-bind:value="key_grupo">{{ option_grupo }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sexo" class="col-md-4 col-form-label text-right">Sexo</label>
                            <div class="col-md-8">
                                <select name="sexo" v-model="form_values.sexo" class="form-control">
                                    <option v-for="(option_sexo, key_sexo) in options_sexo" v-bind:value="key_sexo">{{ option_sexo }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico</label>
                                
                            <div class="col-md-8">
                                <input
                                    name="email" class="form-control" title="Correo electrónico"
                                    v-bind:class="{ 'is-invalid': validation.email_unique == 0, 'is-valid': validation.email_unique == 1 }"
                                    v-model="form_values.email"
                                    v-on:change="validate_form"
                                    >
                                <span class="invalid-feedback">
                                    El correo electrónico ya fue registrado, por favor escriba otro
                                </span>
                            </div>
                        </div>

                        <div class="form-group row" id="form-group_document_number">
                            <label for="no_documento" class="col-md-4 col-form-label text-right">No. Documento</label>
                            <div class="col-md-4">
                                <input
                                    name="no_documento" class="form-control"
                                    title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                                    pattern=".{5,}[0-9]"
                                    v-bind:class="{ 'is-invalid': validation.document_number_unique == 0, 'is-valid': validation.document_number_unique == 1 && form_values.document_number > 0 }"
                                    v-model="form_values.no_documento"
                                    v-on:change="validate_form"
                                    >
                                <span class="invalid-feedback">
                                    El número de documento escrito ya fue registrado para otro usuario
                                </span>
                            </div>
                            <div class="col-md-4">
                                <select name="tipo_documento_id" v-model="form_values.tipo_documento_id" class="form-control">
                                    <option v-for="(option_document_type, key_document_type) in options_document_type" v-bind:value="key_document_type">{{ option_document_type }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="notas" class="col-md-4 col-form-label text-right">Notas sobre usuario</label>
                            <div class="col-md-8">
                                <textarea
                                    name="notas" type="text" class="form-control" rows="3"
                                    title="Notas sobre usuario"
                                    v-model="form_values.notas"
                                ></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-primary w120p" type="submit">Guardar</button>
                            </div>
                        </div>
                    <fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
    var form_values = {
        nombre: '<?= $row->nombre ?>',
        apellidos: '<?= $row->apellidos ?>',
        username: '<?= $row->username ?>',
        email: '<?= $row->email ?>',
        no_documento: '<?= $row->no_documento ?>',
        tipo_documento_id: '0<?= $row->tipo_documento_id ?>',
        sexo: '0<?= $row->sexo ?>',
        grupo_id: '0<?= $row->grupo_id ?>',
        notas: '<?= $row->notas ?>',
    };

// Vue App
//-----------------------------------------------------------------------------
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>',
            validation: {
                document_number_unique: -1,
                username_unique: -1,
                email_unique: -1
            },
            options_document_type: <?= json_encode($options_document_type) ?>,
            options_sexo: <?= json_encode($options_sexo) ?>,
            options_grupo: <?= json_encode($options_grupo) ?>,
            loading: false
        },
        methods: {
            validate_form: function() {
                axios.post(url_app + 'usuarios/validate/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    this.validation = response.data.validation
                })
                .catch(function (error) { console.log(error) })
            },
            validate_send: function () {
                axios.post(url_app + 'usuarios/validate/' + this.row_id, $('#edit_form').serialize())
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
                axios.post(url_app + 'usuarios/update/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    if (response.data.saved_id > 0) toastr['success']('Guardado')
                    this.loading = false
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
            }
        }
    });
</script>