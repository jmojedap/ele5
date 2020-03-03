<?php
    $i = 0;
    $editable = 0;
    if ( $this->session->userdata('rol_id') <= 1 ) { $editable = 1; }
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    
    var base_url = '<?= base_url() ?>';
    var institucion_id = <?= $row->id ?>;
    var flipbook_id = 0;

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('.btn_quitar_flipbook').click(function(){
            flipbook_id = $(this).data('flipbook_id');
            quitar_flipbook();
        });
        
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function quitar_flipbook()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'instituciones/quitar_flipbook/',
            data: {
                institucion_id : institucion_id,
                flipbook_id : flipbook_id
            },
            beforeSend: function(){
                $('.flipbook_' + flipbook_id).addClass('danger');
            },
            success: function(cant_eliminados){
                if ( cant_eliminados > 0 ) { $('.flipbook_' + flipbook_id).hide('slow'); }
            }
        });
    }
</script>

<table class="table table-hover bg-blanco" cellspacing="0">
    <thead>
        <th width="20px">No.</th>
        <th width="30px" class="warning">ID</th>
        <th>Nombre</th>
        <th>Área</th>
        <th>Taller asociado</th>
        <th width="35px" class="hidden-xs"></th>
    </thead>
    <tbody>
        <?php foreach ($flipbooks->result() as $row_flipbook): ?>
            <?php $row_flipbook_full = $this->Pcrn->registro_id('flipbook', $row_flipbook->flipbook_id); ?>
            <?php $i += 1; ?>
            <tr class="flipbook_<?= $row_flipbook->flipbook_id ?>">
                <td><?= $i ?></td>
                <td class="warning"><?= $row_flipbook->flipbook_id ?></td>
                <td><?= anchor("flipbooks/abrir/{$row_flipbook->flipbook_id}", $this->App_model->nombre_flipbook($row_flipbook->flipbook_id), 'target="_blank"') ?></td>
                <td>
                    <span class="etiqueta nivel w1"><?= $row_flipbook_full->nivel ?></span>
                    <?= $this->App_model->etiqueta_area($row_flipbook_full->area_id); ?>
                </td>
                <td>
                    <?= anchor("flipbooks/abrir/{$row_flipbook->taller_id}", $this->App_model->nombre_flipbook($row_flipbook->taller_id), 'target="_blank"') ?>
                </td>
                <td class="hidden-xs">
                    <?php if ( $editable ){ ?>
                        <?php //anchor("institucion/quitar_flipbook/{$row->id}/{$row_resultado->id}", '<i class="fa fa-times"></i>', 'class="a4" title=""') ?>
                        <div class="a4 btn_quitar_flipbook" data-flipbook_id="<?= $row_flipbook->flipbook_id ?>">
                            <i class="fa fa-times"></i>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        <?php endforeach; //Recorriendo flipbooks ?>
    </tbody>
</table>