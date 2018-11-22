<?php
    $offset_actual = $this->input->get('per_page');
    $num_pagina_actual = ceil($offset_actual/$per_page) + 1;    
    $rango_paginas = 4;
    
    $cant_paginas = ceil($cant_resultados/$per_page);
    $offset_paginacion = 0;
    $offset_ultimo = $per_page * ($cant_paginas - 1) ;
    
    $i_desde = $this->Pcrn->limitar_entre($num_pagina_actual - $rango_paginas, 1, $num_pagina_actual - 1);
    $i_hasta = $this->Pcrn->limitar_entre($num_pagina_actual + $rango_paginas, $num_pagina_actual + 1, $cant_paginas);
    
    $offset_ant = $this->Pcrn->limitar_entre($offset_actual - $per_page, 0, $offset_actual);
    $offset_sig = $this->Pcrn->limitar_entre($offset_actual + $per_page, $offset_actual, $cant_resultados);
?>

<!-- Paginación escritorio -->

<div class="btn-group hidden-xs hidden-sm">
    <a href="<?= $url_paginacion . "per_page={$offset_ant}" ?>" class="btn btn-default">
        <i class="fa fa-caret-left"></i>
    </a>
    <a href="<?= $url_paginacion . "per_page={$offset_sig}" ?>" class="btn btn-default">
        <i class="fa fa-caret-right"></i>
    </a>
    <button type="button" class="btn btn-default dropdown-toggle w3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Pág <?= $num_pagina_actual ?>/<?= $cant_paginas ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        
        <li>
            <a href="<?= $url_paginacion ?>">Primera</a>
        </li>
        <li role="separator" class="divider"></li>
        
        <?php for ($i = $i_desde; $i <= $i_hasta; $i++) { ?>
            <?php
                $offset_paginacion = ($i - 1) * $per_page;
                $clase_link = $this->Pcrn->clase_activa($offset_paginacion, $offset, 'active');
            ?>
            
            <li class="<?= $clase_link ?>">
                <a href="<?= $url_paginacion . "&per_page={$offset_paginacion}" ?>">
                    Pág <?= $i ?>
                </a>
            </li>
        <?php } ?>

        <li role="separator" class="divider"></li>
        <li>
            <a href="<?= $url_paginacion . "per_page={$offset_ultimo}" ?>">
                Última
            </a>
        </li>
    </ul>
    
</div>

<!--Paginación dispositivos móviles-->

<div class="btn-group hidden-lg" role="group" aria-label="">
    <a href="<?= $url_paginacion . "per_page={$offset_ant}" ?>" class="btn btn-default w2">
        <i class="fa fa-caret-left"></i>
    </a>
    <a href="<?= $url_paginacion . "per_page={$offset_ant}" ?>" class="btn btn-default">
        <?= $offset_actual + 1 ?> - <?= $offset_actual + $per_page ?>
    </a>
    <a href="<?= $url_paginacion . "per_page={$offset_sig}" ?>" class="btn btn-default w2">
        <i class="fa fa-caret-right"></i>
    </a>
</div>