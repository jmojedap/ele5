<?php $this->load->view('assets/lightbox2') ?>

<div id="postFiles">
    <div class="card center_box_750 mb-2" v-show="files.length < 15">
        <div class="card-body">
            <?php $this->load->view('common/bs4/upload_file_form_v') ?>
        </div>
    </div>
    <div class="text-center my-2">
        <strong class="text-primary">{{ files.length }}</strong> archivos
    </div>
    <div class="text-center my-2" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="center_box_750">
        <table class="table bg-white">
            <thead>
                <th>Archivos</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="(file, fileKey) in files">
                    <td>
                        <a v-bind:href="file.url" target="_blank">
                            {{ file.title }}
                        </a>
                    </td>
                    <td width="190px">
                        <button class="btn btn-light btn-sm"
                            v-on:click="updatePosition(file.id, parseInt(file.position) - 1)" v-show="file.position > 0">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button class="btn btn-light btn-sm"
                            v-on:click="updatePosition(file.id, parseInt(file.position) + 1)"
                            v-show="file.position < (files.length-1)">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <a v-bind:href="`<?= URL_ADMIN . "files/edit/" ?>` + file.id" class="btn btn-sm btn-light"
                            target="_blank" title="Editar filen">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        <button class="btn btn-sm btn-light" v-on:click="setCurrent(fileKey)" data-toggle="modal"
                            data-target="#delete_modal" title="Eliminar filen"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<?php $this->load->view($this->views_folder . 'files/vue_v') ?>