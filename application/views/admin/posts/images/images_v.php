<?php $this->load->view('assets/lightbox2') ?>

<div id="postImages">
    <div class="card center_box_750 mb-2" v-show="images.length < 50">
        <div class="card-body">
            <?php $this->load->view('common/upload_file_form_v') ?>
        </div>
    </div>
    <div class="text-center my-2">
        <strong class="text-primary">{{ images.length }}</strong> imágenes
    </div>
    <div class="text-center my-2" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div id="image_gallery">
        <div class="card w100pc" v-for="(image, imageKey) in images">
            <a v-bind:href="image.url" data-lightbox="image-1">
                <img class="card-img-top" v-bind:alt="image.title" v-bind:src="image.url_thumbnail">
            </a>
            <div class="p-1">
                <button class="btn btn-sm" v-on:click="setMainImage(imageKey)" v-bind:class="{'btn-primary': image.main == 1, 'btn-light': image.main == 0 }" title="Establecer como principal">
                    <i class="far fa-check-circle" v-show="image.main == 1"></i>
                    <i class="far fa-circle" v-show="image.main == 0"></i>
                </button>
                <button class="btn btn-light btn-sm" v-on:click="updatePosition(image.id, parseInt(image.position) - 1)" v-show="image.position > 0">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <button class="btn btn-light btn-sm" v-on:click="updatePosition(image.id, parseInt(image.position) + 1)" v-show="image.position < (images.length-1)">
                    <i class="fa fa-arrow-right"></i>
                </button>
                <a v-bind:href="`<?= URL_ADMIN . "files/edit/" ?>` + image.id" class="btn btn-sm btn-light" target="_blank" title="Editar imagen">
                    <i class="fa fa-pencil-alt"></i>
                </a>
                <button class="btn btn-sm btn-light" v-on:click="setCurrent(imageKey)" data-toggle="modal" data-target="#delete_modal" title="Eliminar imagen"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<?php $this->load->view($this->views_folder . 'images/vue_v') ?>