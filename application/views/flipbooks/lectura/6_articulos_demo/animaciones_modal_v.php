<!-- Modal -->
<div v-for="animacion in bookData.animaciones" class="modal fade" v-bind:id="'modal_' + animacion.archivo_id" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ currentArticulo.titulo }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <video width="640" height="360" controls="">
                <source v-bind:src="'<?php echo URL_UPLOADS ?>/' + animacion.ubicacion" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</div>