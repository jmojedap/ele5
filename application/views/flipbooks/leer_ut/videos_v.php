<a
    class="btn btn-light btn-block mb-2"
    type="button"
    data-toggle="modal"
    v-bind:href="'#modal_' + animacion.archivo_id"
    title="Recurso de animación"
    v-for="animacion in data.animaciones"
    v-show='num_pagina == animacion.num_pagina'
   >
   <img src="<?= URL_IMG ?>flipbook/v5_video.png">
</a>

<div v-for="animacion in data.animaciones" v-bind:id="'modal_' + animacion.archivo_id" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="text-center p-1">
                <video width="640" height="360" controls="">
                    <source v-bind:src="'<?= URL_UPLOADS ?>/' + animacion.ubicacion" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>