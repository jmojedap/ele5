<?php
    $clase_resultado = 'danger';
    if ( $valido ) {
        $clase_resultado = 'success';
    }
    
    $detalle_no_importados = implode(', ', $no_importados);
    
    $clase_no_importados = '';
    if ( count($no_importados) > 0 ) 
    {
        $clase_no_importados = 'warning';
    }
    
    $cant_importados = count($array_hoja) - count($no_importados);
    
?>

<?php if ( isset($vista_menu) ) { ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<?php if ( isset($vista_submenu) ) { ?>
    <?php $this->load->view($vista_submenu); ?>
<?php } ?>

<table class="table table-hover bg-blanco">
    <tbody>
        <tr class="<?= $clase_resultado ?>">
            <td width="250px">Resultado</td>
            <td><?= $mensaje ?></td>
        </tr>
        <tr>
            <td width="250px">Hoja de cálculo</td>
            <td><?= $nombre_hoja ?></td>
        </tr>
        <tr>
            <td>Filas encontradas</td>
            <td><?= count($array_hoja) ?></td>
        </tr>
        <tr>
            <td>Filas importadas</td>
            <td><?= $cant_importados ?></td>
        </tr>
        <tr class="<?= $clase_no_importados ?>">
            <td>Filas no importadas</td>
            <td>
                <?= count($no_importados) ?> 
                <?php if ( count($no_importados) > 0 ){ ?>
                    <button style="margin-left: 10px;" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#detalle_no_importados" aria-expanded="false" aria-controls="detalle_no_importados">
                        Ver detalle
                    </button>
                <?php } ?>
            </td>
        </tr>
        <tr class="collapse" id="detalle_no_importados">
            <td>Números de las filas no importadas</td>
            <td>
                <?= $detalle_no_importados ?>
            </td>
        </tr>
    </tbody>
</table>

<?php if ( isset($destino_volver) ) { ?>
    <?= anchor($destino_volver, '<i class="fa fa-arrow-circle-left"></i> Volver', 'class="btn btn-default"') ?>
<?php } ?>

<?php if ( isset($vista_importados) ) { ?>
    <?php $this->load->view($vista_importados); ?>
<?php } ?>

