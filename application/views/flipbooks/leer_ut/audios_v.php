<a
    class="btn btn-light btn-block mb-2"
    type="button"
    data-toggle="modal"
    v-bind:href="'#modal_' + audio.archivo_id"
    title="Recurso de audio"
    v-for="audio in data.audios"
    v-show='num_pagina == audio.num_pagina'
   >
    <img src="<?= URL_IMG ?>flipbook/v5_audio.png">
</a>

<div v-for="audio in data.audios" v-bind:id="'modal_' + audio.archivo_id" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gridSystemModalLabel">
                    Audio del tema
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <audio controls>
                    <source v-bind:src="'<?= $carpeta_uploads ?>' + audio.ubicacion" type="audio/mp3">
                </audio>
            </div>
        </div>
    </div>
</div>