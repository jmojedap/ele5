

<div class="row">
    <div class="col-md-4">
        <div class="list-group">
            <?php foreach ( $ledins->result() as $row_ledin ) { ?>
                <?php
                    $cl_item = $this->Pcrn->clase_activa($row_ledin->id, $ledin_id, 'active');
                ?>
                <a href="<?php echo base_url("temas/lecturas_dinamicas/{$row->id}/{$row_ledin->id}") ?>" class="list-group-item list-group-item-action <?php echo $cl_item ?>">
                    <?php echo $row_ledin->nombre_post ?>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card" style="max-width: 750px;">
            <div class="card-body">
                <?php $this->load->view('temas/ledins/ledin_v') ?>
            </div>
        </div>
    </div>
</div>