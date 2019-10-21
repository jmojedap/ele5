

<div class="row">
    <div class="col-md-4">
        <div class="list-group">
            <?php foreach ( $diccionarios->result() as $row_diccionario ) { ?>
                <?php
                    $cl_item = $this->Pcrn->clase_activa($row_diccionario->id, $diccionario_id, 'active');
                ?>
                <a href="<?php echo base_url("temas/diccionarios/{$row->id}/{$row_diccionario->id}") ?>" class="list-group-item list-group-item-action <?php echo $cl_item ?>">
                    <?php echo $row_diccionario->nombre_post ?>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card" style="max-width: 750px;">
            <div class="card-body">
                <?php $this->load->view('temas/diccionarios/diccionario_v') ?>
            </div>
        </div>
    </div>
</div>