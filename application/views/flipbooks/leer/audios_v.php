<a
    class="btn btn-default btn-block"
    type="button"
    data-toggle="modal"
    v-bind:href="'#modal_' + audio.archivo_id"
    title="Recurso de audio"
    v-for="audio in data.audios"
    v-show='num_pagina == audio.num_pagina'
   >
    <img v-bind:src="'<?php echo URL_IMG . 'flipbook/' ?>' + audio.icono">
</a>

<div v-for="audio in data.audios" v-bind:id="'modal_' + audio.archivo_id" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">
                    Audio del tema
                </h4>
            </div>
            <div class="modal-body text-center">
                <audio controls>
                    <source v-bind:src="'<?php echo $carpeta_uploads ?>' + audio.ubicacion" type="audio/mp3">
                </audio>
            </div>
        </div>
    </div>
</div>