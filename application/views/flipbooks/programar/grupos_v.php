<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    $seccion = $this->uri->segment(2);

    //Opciones institución
        $opciones_institucion = $this->Pcrn->query_to_array($instituciones, 'nombre_institucion', 'institucion_id');
?>

<script>
    
// Variables
//-----------------------------------------------------------------------------
    var base_url_g = '<?= base_url("flipbooks/{$seccion}/{$row->id}") ?>';
    var institucion_id = <?= $institucion_id ?>;
    
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#i').change(function(){
            institucion_id = $(this).val();
            window.location = base_url_g + '/?i=' + institucion_id;
        });
        
    });
</script>

<div class="sep2">
    <div class="row">
        <div class="col-md-3">
            <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
                <?= form_dropdown('i', $opciones_institucion, $institucion_id, 'id="i" class="form-control chosen-select"') ?>
            <?php } ?>
        </div>
        <div class="col-md-9">
            <div class="btn-group" role="group" aria-label="...">
                <a class="w3 btn btn-default">Grupo:</a>
                <?php foreach ($grupos->result() as $row_grupo) : ?>
                    <?php
                        $clase_grupo = 'w3 btn btn-default';
                        if ( $grupo_id == $row_grupo->id ) { $clase_grupo = 'w3 btn btn-primary'; }
                    ?>
                    <?= anchor("flipbooks/{$seccion}/{$row->id}/?i={$institucion_id}&g={$row_grupo->id}", "{$row_grupo->nivel}-{$row_grupo->grupo}", 'class="' . $clase_grupo . '" title=""') ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>      
</div>