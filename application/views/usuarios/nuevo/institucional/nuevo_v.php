<div id="app_add">
    <div class="container">
        <div class="card" v-show="form_values.institucion_id == 0">
            <div class="card-body">
                <h3 class="card-title">Seleccione la institución</h3>
                <fieldset v-bind:disabled="loading">
                    <div class="form-group row">
                        <label for="q" class="col-md-4 col-form-label text-right">Buscar institución</label>
                        <div class="col-md-8">
                            <input
                                name="q" type="text" class="form-control" required title="Institución"
                                v-model="instituciones_q" v-on:change="get_instituciones"
                            >
                        </div>
                    </div>
                <fieldset>
                <table class="table bg-white" v-show="instituciones.length > 0">
                    <thead>
                        <th width="20px">ID</th>
                        <th>Institución</th>
                        <th>Ciudad</th>
                        <th width="50px"></th>
                    </thead>
                    <tbody>
                        <tr v-for="(institucion, key) in instituciones">
                            <td>{{ institucion.id }}</td>
                            <td>{{ institucion.nombre_institucion }}</td>
                            <td>{{ institucion.ciudad }}</td>
                            <td>
                                <button class="btn btn-primary" v-on:click="set_institucion(key)">Seleccionar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" v-show="form_values.institucion_id > 0">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="add_form" @submit.prevent="validate_send">
                    <input name="institucion_id" class="d-none" v-model="form_values.institucion_id">
                    <fieldset v-bind:disabled="loading">
                        <div class="form-group row">
                            <label for="institucion" class="col-md-4 col-form-label text-right">Institución</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input
                                        type="text" class="form-control" disabled
                                        v-model="institucion.nombre_institucion"
                                    >
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" v-on:click="unset_institucion">Cambiar</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="rol_id" class="col-md-4 col-form-label text-right">Rol <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="rol_id" class="form-control" required v-model="form_values.rol_id">
                                    <option v-for="(option_rol, key_rol) in options_rol" v-bind:value="key_rol">{{ option_rol }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nombre" class="col-md-4 col-form-label text-right">Nombres <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input
                                    name="nombre" type="text" class="form-control"
                                    required
                                    title="Nombre" placeholder=""
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
                                    title="Apellidos" placeholder=""
                                    v-model="form_values.apellidos"
                                >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-right">Username <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input
                                    name="username" class="form-control"
                                    title="Puede contener letras y números, entre 6 y 25 caractéres, no debe contener espacios ni caracteres especiales"
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
                            <label for="sexo" class="col-md-4 col-form-label text-right">Sexo <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select name="sexo" v-model="form_values.sexo" class="form-control" required>
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

<?php $this->load->view('usuarios/nuevo/institucional/vue_v') ?>