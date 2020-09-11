<?php
    //Imágenes
    $carpeta_paginas = RUTA_UPLOADS . 'pf_mini/';
    
    $src_alt = base_url() . RUTA_IMG . 'app/pf_nd_1.png';   //Imagen alternativa

    $att_mini = array(
        'title' =>  'Imagen',
        'class' =>  'pf',
        'width'  => '80px',
        'onError' => "this.src='" . $src_alt . "'", //Imagen alternativa
    );
?>

<div class="row">
    <div class="col-md-3">
        <ul class="list-group">
            <?php foreach ($flipbooks->result() as $row_flipbook) : ?>
                <?php
                    $clase = '';
                    if ( $row_flipbook->flipbook_id == $flipbook_id ) { $clase = 'active'; }
                ?>
                <a role="presentation" class="list-group-item <?= $clase ?>" href="<?php echo base_url("usuarios/anotaciones/{$row->id}/{$row_flipbook->flipbook_id}") ?>">
                    <?= $row_flipbook->nombre_flipbook ?>
                </a>    
            <?php endforeach ?>
        </ul>
    </div>

    <div class="col-md-9">
        <div style="max-width: 750px">
            <?php foreach ($anotaciones->result() as $row_anotacion): ?>
                <?php
                    $row_pf = $this->Pcrn->registro_id('pagina_flipbook', $row_anotacion->pagina_id);
                    $att_mini['src'] = "{$carpeta_paginas}{$row_pf->archivo_imagen}";
                    $att_mini['class'] = 'card-img';
                ?>

                <div class="card mb-2">
                    <div class="row no-glutters">
                        <div class="col-md-3">
                            <?= img($att_mini); ?>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $this->App_model->nombre_tema($row_pf->tema_id) ?>
                                </h5>
                                <p class="card-text">
                                    <?= $row_anotacion->anotacion ?>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <?= $this->Pcrn->fecha_formato($row_anotacion->editado, 'Y-M-d') ?> | Hace <?= $this->Pcrn->tiempo_hace($row_anotacion->editado) ?>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

