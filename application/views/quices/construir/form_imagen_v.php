<h4 class="card-title">Imagen de fondo</h4>

<?php if ( $this->session->flashdata('message') ){ ?>
    <?= $this->session->flashdata('message') ?>
<?php } ?>

<form action="<?php echo base_url("quices/cargar_imagen/{$row->id}") ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">

    <input type="hidden" name="quiz_id" value="<?php echo $row->id ?>"">
    <div class="form-group">
        <input type="file" name="archivo" class="form-control-file" required>
    </div>

    <div class="form-group">
        <button class="btn btn-primary w4">
            Cargar
        </button>
    </div>
</form>