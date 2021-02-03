<div id="pays_app">
    <div class="card center_box_750">
        <div class="card-body">
            <h1 class="text-center">Pagos En Línea Editores</h1>

            <div class="mb-2" v-show="step == 1">
                <p class="text-center">Realizar pago</p>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-lg btn-info btn-block" v-on:click="set_code_type('institution')">
                            Por Código de Institución
                        </button>
                        <p class="mt-2 text-center">
                            Ingrese CÓDIGO INSTITUCIÓN y siga los pasos indicados.
                        </p>
                    </div>
                    <div class="col">
                        <button class="btn btn-lg btn-primary btn-block" v-on:click="set_code_type('user')">
                            Por Código de Usuario
                        </button>
                        <p class="mt-2 text-center">
                            Ingrese CÓDIGO USUARIO y siga los pasos indicados.
                        </p>
                    </div>
                </div>
            </div>

            <div v-show="step == 2">
                <button class="btn btn-secondary w120p mb-2" v-on:click="set_step(1)">
                    <i class="fa fa-arrow-left"></i> Atrás
                </button>
                <form accept-charset="utf-8" method="POST" id="institution_form" @submit.prevent="get_institutions" v-show="code_type == 'institution'">
                    <div class="form-group row">
                        <label for="cod" class="col-md-4 col-form-label text-right">Código Institución</label>
                        <div class="col-md-5">
                            <input
                                name="cod" id="field-cod" type="text" class="form-control"
                                required
                                title="Código institución" placeholder=""
                                v-model="institution_cod"
                            >
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" type="submit">
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>
                <table class="table" v-show="code_type == 'institution' && ! no_institutions">
                    <thead>
                        <th>Nombre institución</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="(institution, institution_key) in institutions">
                            <td>{{ institution.name }}</td>
                            <td style="width: 150px;">
                                <button class="btn btn-primary btn-block" v-on:click="set_current(institution_key)">Continuar <i class="fa fa-arrow-right ml-2"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="alert alert-info" v-show="no_institutions">
                    <i class="fa fa-info-circle"></i>
                    No se encontraron instituciones con el código <strong>"{{ institution_cod }}"</strong>
                </div>

                

                <form accept-charset="utf-8" method="POST" id="user_form" @submit.prevent="get_user" v-show="code_type == 'user'">
                    <div class="form-group row">
                        <label for="cod" class="col-md-4 col-form-label text-right">Código Usuario:</label>
                        <div class="col-md-5">
                            <input
                                name="username" id="field-username" type="text" class="form-control"
                                required
                                title="Código estudiante" placeholder=""
                                v-model="username"
                            >
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" type="submit">
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>

                <div class="alert alert-info" v-show="no_users">
                    <i class="fa fa-info-circle"></i>
                    No se encontró usuario con el código: <strong>"{{ username }}"</strong>
                </div>
                
            </div>

            <div class="products" v-show="step == 3">
                <button class="btn btn-secondary w120p mb-2" v-on:click="set_step(2)">
                    <i class="fa fa-arrow-left"></i> Atrás
                </button>
                <h2 class="text-success" v-show="user.id > 0">{{ user.nombre }} {{ user.apellidos }}</h2>
                <h2 class="text-success">{{ curr_institution.name }}</h2>
                <h3>Pagos asociados</h3>
                <table class="table">
                    <tbody>
                        <tr v-for="(product, product_key) in products">
                            <td>
                                <p style="font-size: 1.2em">
                                    <strong>{{ product.name }}</strong>
                                </p>
                                <p class="price text-success">{{ product.price | currency }}</p>
                                <p>
                                   Nivel escolar: <strong>{{ product.level | nivel_name }}</strong>
                                </p>

                            </td>
                            <td>
                                <button class="btn btn-primary btn-block" v-on:click="add_product(product_key)">
                                    Continuar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<?php $this->load->view('orders/pays/vue_v') ?>