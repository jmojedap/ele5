<?php
    $modulo_ant = '';
?>

<style>
    .bitacora h1{
        color: #333;
    }

    .bitacora h2{
        color: #FFF;
        background-color: #4fc3f7;
        padding: 3px;
    }

    .bitacora h3{
        color: #89CB4E;
    }
</style>

<div class="row">
    <div class="col-md-3">
        <h4 class="text-center">Pago desarrollo</h4>
        <div class="list-group">
            <?php foreach ( $pagos->result() as $row_pago ) { ?>
                <?php
                    $cl_activa = $this->Pcrn->clase_activa($row_pago->id, $pago_id, 'active');
                ?>
                <a href="<?php echo base_url("posts/bitacora/{$row_pago->id}") ?>" class="list-group-item list-group-item-action <?php echo $cl_activa ?>">
                    <?php echo $row_pago->nombre_post ?>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card bitacora" style="max-width: 650px;">
            <div class="card-body">
                <div class="text-center">
                    <h1>INFORME DE ACTIVIDAD</h1>
                    <h3><?php echo $this->Pcrn->campo_id('post', $pago_id, 'nombre_post') ?></h3>
                </div>
                <?php foreach ( $bitacora->result() as $row_bitacora ) { ?>
                    <?php
                        $cl_costo = ( $row_bitacora->costo > 0) ? 'alert-info' : 'alert-success';
                    ?>
                    <?php if ( $row_bitacora->modulo != $modulo_ant ) { ?>
                        <h2><?php echo $row_bitacora->modulo ?></h2>
                    <?php } ?>
                    <h3>
                        <?php echo $row_bitacora->elemento ?>: <?php echo $row_bitacora->nombre_post ?>
                    </h3>
                    
                    <p>
                        <?php echo $this->Pcrn->fecha_formato($row_bitacora->fecha, 'Y-M-d') ?>
                    </p>
                    <?php echo $row_bitacora->contenido ?>
                    <br>

                        <?php if ( $row_bitacora->costo > 0 ) { ?>
                            <div class="alert <?php echo $cl_costo ?>">
                                Costo: <b>$<?php echo number_format($row_bitacora->costo, 0) ?></b>
                            </div>
                        <?php } else { ?>
                            <div class="alert <?php echo $cl_costo ?>">
                                Costo: <b>$0</b> (Costo incluído en pagos del contrato de soporte permanente)
                            </div>
                        <?php } ?>


                    <br/>
                    <a href="<?php echo base_url("posts/editar/{$row_bitacora->id}") ?>" class="btn btn-success btn-sm" target="_blank">Editar</a>
                    <br/>
                    <hr>
                <?php
                    $modulo_ant = $row_bitacora->modulo;
                ?>
                <?php } ?>
            </div>
        </div>
        
    </div>
</div>
