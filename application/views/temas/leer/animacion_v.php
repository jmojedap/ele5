<a href="#modal_<?= $row_archivo->archivo_id ?>" class="modalLinkVideo recurso oculto <?= 'pagina_' . $row_archivo->num_pagina ?>">
    <img src="<?= base_url() . RUTA_IMG . 'flipbook/' . $row_archivo->icono ?>"/>
</a>

<div id="modal_<?= $row_archivo->archivo_id ?>" class="modal_video">
    <div class="div3" style="text-align: right;">
        <button class="closeBtn button orange small"><i class="fa fa-times"></i></button>
    </div>
    <div class="div3">
        <video width="640" height="360" controls>
            <source id="source_<?= $row_archivo->archivo_id ?>" src="<?= $carpeta_uploads . $row_archivo->ubicacion ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>