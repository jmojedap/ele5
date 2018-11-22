<?php
    $num_registro = 0;
    $carpeta_img = base_url() . RUTA_UPLOADS . 'pf_mini/';
?>

<?php if ( $this->session->flashdata('resultado') != NULL ):?>
    <?php $resultado = $this->session->flashdata('resultado') ?>
    <h4 class="alert_success"><?= $resultado['mensaje'] ?></h4>
<?php endif ?>

<?= anchor("paginas/cargar/{$row->id}/0/tema", 'Insertar página al inicio', 'class="btn btn-default sep1"') ?>
    
<?php foreach ($paginas->result() as $row_pagina): ?>
    <?php   
        $num_subir = $num_registro - 1;
        $num_bajar = $num_registro + 1;
        $img_pagina = $this->Pagina_model->img_pf($row_pagina, 1);
        $num_pagina_mostrar = $row_pagina->orden + 1;
        $row_tema = $this->Pcrn->registro_id('tema', $row_pagina->tema_id);
    ?>
    
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="pf_img_mini">
                <?= anchor("paginas/ver/{$row_pagina->id}", $img_pagina) ?>
            </div>

            <div class="pf_datos">
                <h2 class="etiqueta informacion w1"><?= $num_pagina_mostrar ?></h2>
                <br/>

                <span class="etiqueta primario"><?= substr('0000000' . $row_pagina->id, -7) ?></span>

                <br/>
                <span class="suave"> <?= $row_pagina->titulo_pagina ?></span>

                <br/>
                <span class="suave"><?= $row_pagina->archivo_imagen ?></span>


                <p>
                    <?= anchor("temas/mover_pagina/{$row->id}/{$row_pagina->id}/{$num_subir}", '<i class="fa fa-caret-up"></i>', 'class="btn btn-default btn-sm"') ?>
                    <?= anchor("temas/mover_pagina/{$row->id}/{$row_pagina->id}/{$num_bajar}", '<i class="fa fa-caret-down"></i>', 'class="btn btn-default btn-sm"') ?>
                    <?= $this->Pcrn->anchor_confirm("temas/quitar_pf/{$row->id}/{$row_pagina->id}", '<i class="fa fa-times"></i>', 'class="btn btn-default btn-sm" title="Quitar página de este libro"', '¿Desea quitar esta página del libro?') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="sep2">
        <?= anchor("paginas/cargar/{$row->id}/{$num_pagina_mostrar}/tema", 'Insertar página aquí', 'class="btn btn-default"') ?>
    </div>

<?php endforeach ?>