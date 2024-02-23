<div id="addPostApp">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="postForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <label for="tipo_id" class="col-md-4 col-form-label text-right">Tipo</label>
                        <div class="col-md-8">
                            <select name="tipo_id" v-model="fields.tipo_id" class="form-control" required>
                                <option v-for="optionType in arrType" v-bind:value="optionType.cod">{{ optionType.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nombre_post" class="col-md-4 col-form-label text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                class="form-control" name="nombre_post" required autofocus
                                v-model="fields.nombre_post">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="offset-md-4 col-md-8 col-sm-12">
                            <button class="btn btn-success w120p" type="submit">
                                <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                                Crear
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_created" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Post creado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    Post creado correctamente
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="goToCreated">
                        Abrir post
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="clearForm" data-dismiss="modal">
                        <i class="fa fa-plus"></i>
                        Crear otro
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view($this->views_folder . 'add/vue_v');