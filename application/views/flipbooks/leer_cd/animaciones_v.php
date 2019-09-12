<a
    class="btn btn-default btn-block"
    type="button"
    data-toggle="modal"
    v-bind:href="'#modal_' + animacion.archivo_id"
    title="Recurso de animación"
    v-for="animacion in data.animaciones"
    v-show='num_pagina == animacion.num_pagina'
   >
    <img v-bind:src="'<?php echo URL_IMG . 'flipbook/' ?>' + animacion.icono">
</a>

<div v-for="animacion in data.animaciones" v-bind:id="'modal_' + animacion.archivo_id" class="modal fade modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <video width="640" height="360" controls="">
                    <source v-bind:src="'<?php echo URL_UPLOADS ?>/' + animacion.ubicacion" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            
        </div>
    </div>
</div>