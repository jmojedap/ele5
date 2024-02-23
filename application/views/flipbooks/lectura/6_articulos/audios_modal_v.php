<!-- Modal -->
<div v-for="audio in bookData.audios" class="modal fade" v-bind:id="'modal_' + audio.archivo_id" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ currentArticulo.titulo }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <audio controls>
                    <source v-bind:src="'<?php echo $carpeta_uploads ?>' + audio.ubicacion" type="audio/mp3">
                </audio>
            </div>
        </div>
    </div>
</div>