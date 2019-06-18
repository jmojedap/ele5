<div class="mb-2">
    <span class="etiqueta nivel w1"> <?= $row->nivel ?></span>
    <?= $this->App_model->etiqueta_area($row->area_id) ?>
    
    <span class="text-muted"> | </span>

    <span class="text-muted">Páginas</span>
    <span class="text-danger"> <?= $row->num_paginas ?></span> |     

    <?php if ( $this->session->userdata('srol') == 'interno' ){ ?>

        <span class="text-muted">Tipo</span>
        <span class="text-danger"> <?= $this->Item_model->nombre(11, $row->tipo_flipbook_id); ?></span> |

        <?php
            $link_taller = $this->App_model->nombre_flipbook($row->taller_id);
            if ( ! is_null($row->taller_id) ) { $link_taller = anchor("flipbooks/ver_flipbook/{$row->taller_id}", $link_taller, 'target="_blank"'); }
        ?>
        <span class="text-muted">Taller asociado</span>
        <span class="text-danger"> <?= $link_taller ?></span>
    <?php } ?>
</div>