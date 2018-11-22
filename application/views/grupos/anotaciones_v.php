<?php $this->load->view('assets/chosen_jquery'); ?>

<?php
    $i = 0;
    
    //$opciones_flipbook[''] = '[ Filtrar por contenido ]';
    foreach( $flipbooks->result() as $row_flipbook ) {
        $opciones_flipbook['0' . $row_flipbook->flipbook_id] = $this->App_model->nombre_flipbook($row_flipbook->flipbook_id);
    }
    
    $opciones_tema[''] = '[ Todos los temas ]';
    foreach( $temas->result() as $row_tema ) {
        $opciones_tema['0' . $row_tema->tema_id] = $this->Pcrn->campo_id('tema', $row_tema->tema_id, 'nombre_tema');
    }
?>

<?php if ( $this->session->userdata('rol_id') <= 2 ){ ?>
    <?php $this->load->view('grupos/submenu_flipbooks_v') ?>
<?php } ?>

<script>
// Variables
//-----------------------------------------------------------------------------
    
    var base_url = '<?= base_url() ?>';
    var elemento_id = <?= $row->id ?>;
    var flipbook_id = '<?= $flipbook_id ?>';
    var tema_id = '<?= $tema_id ?>';
    var controlador = '<?= $controlador ?>';
    var destino = '<?= base_url() ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        
        $('#dp_flipbook_id').change(function(){
            flipbook_id = $('#dp_flipbook_id').val();
            destino = base_url + 'grupos/anotaciones/' + elemento_id + '/' + flipbook_id + '/' + tema_id;
            window.location = destino;
        });
        
        $('#dp_tema_id').change(function(){
            tema_id = $('#dp_tema_id').val();
            destino = base_url + 'grupos/anotaciones/' + elemento_id + '/' + flipbook_id + '/' + tema_id;
            window.location = destino;
        });
        
    });
</script>

<div class="row">
    <div class="col col-md-4">
        <div class="sep1" style="min-height: 400px;">
            <div class="sep1">
                <?= form_dropdown('flipbook_id', $opciones_flipbook, $flipbook_id, 'id="dp_flipbook_id" class="form-control" style="width: 98%"') ?>
            </div>
            <div class="sep1">
                <?= form_dropdown('tema_id', $opciones_tema, $tema_id, 'id="dp_tema_id" class="form-control chosen-select" style="width: 98%"') ?>
            </div>
        </div>
    </div>
    <div class="col col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                Anotaciones
            </div>
            <div class="panel-body">
                <?php foreach ($anotaciones->result() as $row_anotacion) : ?>
                    <div class="sep1">
                        <?= anchor("fsda", $this->App_model->nombre_usuario($row_anotacion->usuario_id, 3), 'class="" title=""') ?><br/>

                        <span class="resaltar"> <?= $row_anotacion->nombre_tema ?></span>
                        <span class="suave"> | </span>

                        <span class="suave"><?= $this->Pcrn->fecha_formato($row_anotacion->editado, 'M-d') ?></span>
                        <span class="suave">, </span>

                        <span class="suave">Hace <?= $this->Pcrn->tiempo_hace($row_anotacion->editado) ?></span>
                        <span class="suave"> | </span>

                        <p>
                            <?= $row_anotacion->anotacion ?>
                        </p>
                        
                        <hr/>

                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>