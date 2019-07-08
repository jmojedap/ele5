
<?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
    <p>
        <span class="suave">Tipo </span>
        <span class="resaltar">
            <?= $row->nombre ?> <?= $this->Item_model->nombre(33, $row->tipo_id) ?>
        </span>
        <span class="suave"> | </span>
    </p>
<?php } ?>