<link href="https://fonts.googleapis.com/css?family=Merriweather&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= URL_RESOURCES ?>css/lectura_dinamica.css">

<?php $this->load->view('temas/lectura_dinamica/script_v') ?>

<div id="ledin">
    <div class="center_box_750">
        <?php if ( ! is_null($ledin) ) { ?>
        <?php
            $elementos = json_decode($ledin->contenido_json);
        ?>
        <h2 class="text-center"><?php echo $ledin->nombre_post ?></h2>

        <?php if ( $ledin->texto_2 ) { ?>
        <img src="<?php echo URL_UPLOADS . 'lecturas_dinamicas_imagenes/' . $ledin->texto_2 ?>" alt="" width="100%"
            class="rounded mb-3">
        <?php } ?>

        <div class="mb-3">
            <button class="btn btn-success w4 stopped" id="btn_play">
                Iniciar Lectura
            </button>
            <button class="btn btn-warning w4 playing btn_stop_ledin">
                Detener
            </button>
            <div class="btn-group stopped" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary" disabled>Velocidad</button>
                <?php foreach ( $arr_lapses as $key => $lapse ) { ?>
                <?php
                            $cl_lapse = $this->Pcrn->clase_activa($key, $lapse_index, 'btn-primary', 'btn-light');
                        ?>
                <button type="button" class="btn w2 btn_speed <?php echo $cl_lapse ?>"
                    data-lapse="<?php echo $lapse ?>">
                    <?php echo $key; ?>
                </button>
                <?php } ?>
            </div>
        </div>

        <?php if ( isset($elementos) ) : ?>
        <div id="lectura_diccionario" class="ledin_contenido stopped">
            <?php echo $elementos->diccionario ?>
        </div>
        <div id="lectura_dinamica" class="ledin_contenido playing">
            <?php echo $elementos->lectura_dinamica ?>
        </div>
        <?php endif; ?>
        <?php } ?>
    </div>

    <!-- Modal Definición -->
    <div class="modal fade" id="definicionModal" tabindex="-1" aria-labelledby="definicionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="definicionModalLabel">Palabras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="definicion"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>