<?php if ( isset($resultado) ):?>
       <h4 class="alert_error"><?= $mensajes ?></h4>
<?php endif ?>

<div class="div2 panel panel-default">
    <div class="panel-body">
        <?= anchor("paginas/cargar/{$row->id}/0", 'Insertar página al inicio', 'class="btn btn-default"') ?>
        <?= anchor("paginas/cargar/{$row->id}/{$paginas->num_rows()}", 'Insertar página al final', 'class="btn btn-default"') ?>
    </div>
</div>


<?php foreach ($paginas->result() as $row_pagina): ?>
    <?php   
        $img_pagina = $this->Pagina_model->img_pf($row_pagina, 1);
        $num_pagina_mostrar = $row_pagina->num_pagina + 1;
        $row_tema = $this->Pcrn->registro_id('tema', $row_pagina->tema_id);
    ?>

    
    <div class="panel panel-default" style="background: white;">
        <div class="panel-body">
            <div class="media">
                <div class="casilla" style="padding: 0 20px 10px 0;">
                    <?= anchor("paginas/ver/{$row_pagina->pagina_id}", $img_pagina) ?>
                </div>
                <div class="media-body">
                    <h2 class="etiqueta informacion w2"><?= $num_pagina_mostrar ?></h2>
                    <h4 class="media-heading"><?= $row_tema->nombre_tema ?></h4>

                    <h5> <?= $row_pagina->titulo_pagina ?></h5>
                    <h5> Código: <?= substr('0000000' . $row_pagina->pagina_id, -7) ?></h5>

                    <p>
                        <?= anchor("flipbooks/mover_pagina/{$row->id}/{$row_pagina->pagina_id}/2", '<i class="fa fa-arrow-up"></i>', 'class="btn btn-default"') ?>
                        <?= anchor("flipbooks/mover_pagina/{$row->id}/{$row_pagina->pagina_id}/1", '<i class="fa fa-arrow-down"></i>', 'class="btn btn-default"') ?>
                        <?= $this->Pcrn->anchor_confirm("flipbooks/quitar_pf/{$row->id}/{$row_pagina->pagina_id}", '<i class="fa fa-times"></i>', 'class="btn btn-default" title="Quitar página de este libro"', '¿Desea quitar esta página del libro?') ?>
                    </p>
                </div>
            </div>
        </div>
        
    </div>

    <div class="div3">
        <?= anchor("paginas/cargar/{$row->id}/{$num_pagina_mostrar}", 'Insertar página aquí', 'class="btn btn-default"') ?>
    </div>

<?php endforeach ?>

        