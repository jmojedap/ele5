<div id="read_app">

    <div class="center_box_750">
        <div class="mb-2">
            <a href="<?= base_url("datos/ayudas") ?>" class="btn btn-light w120p">
                <i class="fa fa-arrow-left"></i>
                Volver
            </a>
            <?php if ( $this->session->userdata('role') <= 2 ) { ?>
                <a href="<?= base_url("posts/edit/{$row->id}") ?>" class="btn btn-primary">
                    <i class="fa fa-pencil-alt"></i> Editar
                </a>
            <?php } ?>
        </div>
        <div class="card">
            <div class="card-body">
                <strong><?= $row->texto_1 ?> / <?= $row->texto_2 ?></strong>
                <h1><?= $head_title ?></h1> 
                <p><?= $row->resumen ?></p>
                <div>
                    <?= $row->contenido ?>
                </div>
            </div>
        </div>
    </div>
</div>