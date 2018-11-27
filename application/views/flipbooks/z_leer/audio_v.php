<a type="button" data-toggle="modal" href="#modal_<?= $row_archivo->archivo_id ?>" class="btn btn-default recurso hidden <?= 'pagina_' . $row_archivo->num_pagina ?>">
    <img src="<?= $carpeta_iconos . $row_archivo->icono ?>"/>
</a>

<div id="modal_<?= $row_archivo->archivo_id ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Audio del tema</h4>
            </div>
            <div class="modal-body text-center">
                <audio controls id="audio_<?= $row_archivo->archivo_id ?>">
                    <source id='source_<?= $row_archivo->archivo_id ?>' src="<?= $carpeta_uploads . $row_archivo->ubicacion ?>" type="audio/mp3">
                </audio>
            </div>
            
        </div>
    </div>
</div>