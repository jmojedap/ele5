<?php
    $num_registro = 0;
?>

<?php if ( $this->session->flashdata('resultado') != NULL ):?>
    <?php $resultado = $this->session->flashdata('resultado') ?>
    <div class="modulo2 width_3_quarter">
        <h4 class="alert_success"><?= $resultado['mensaje'] ?></h4>
    </div>
<?php endif ?>


<article class="module width_full">
    <header>
        <h3>Páginas (<?= $paginas->num_rows() ?>)</h3>
    </header>
    
    <div class="module_content">
        <p>
            <?= anchor("paginas/cargar/{$row->id}/0/tema", 'Insertar página al inicio', 'class="a2"') ?>
        </p>
    </div>
           
    <hr />
    
    <div class="module_content">
        <?php foreach ($paginas->result() as $row_pagina) : ?>
            <?php
                $num_subir = $num_registro - 1;
                $num_bajar = $num_registro + 1;
                $img_pagina = $this->App_model->img_pf($row_pagina, 2);
                $orden_mostrar = $row_pagina->orden + 1;
            ?>
            <div class="pf_mini">
                <div class="pf_img_mini">
                    <?= anchor("flipbooks/ver_pagina/{$row_pagina->id}", $img_pagina) ?>
                </div>

                <div class="pf_datos">
                    <h2><?= $orden_mostrar ?></h2>
                    <h3> <?= $row_pagina->titulo_pagina ?></h3>
                    <h4> Código: <?= substr('0000000' . $row_pagina->id, -7) ?></h4>
                    <p>
                        <?= anchor("flipbooks/ver_pagina/{$row_pagina->id}", 'Detalle', 'class="a2"') ?>
                        <?= anchor("flipbooks/editar_pagina/edit/{$row_pagina->id}", 'Editar', 'class="a2" target="_blank"') ?>
                        <?= $this->Pcrn->anchor_confirm("admin/temas/quitar_pf/{$row->id}/{$row_pagina->id}", 'Quitar...', 'class="a2" title="Quitar página de este tema"', '¿Desea quitar esta página de este tema?') ?>
                        <?= anchor("admin/temas/mover_pagina/{$row->id}/{$row_pagina->id}/{$num_subir}", 'Subir', 'class="a2"') ?>
                        <?= anchor("admin/temas/mover_pagina/{$row->id}/{$row_pagina->id}/{$num_bajar}", 'Bajar', 'class="a2"') ?>
                    </p>
                </div>
                <div class="clear"></div>
            </div>
            <div class="module_content">
                <?= anchor("paginas/cargar/{$row->id}/{$orden_mostrar}/tema", 'Insertar página aquí', 'class="a2"') ?>
            </div>
        
            <?php $num_registro += 1; //Siguiente fila ?>
        
        <?php endforeach ?>
    </div>
    
    
</article>