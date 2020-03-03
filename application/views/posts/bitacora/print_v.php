<?php
    $modulo_ant = '';
    $total_cost = 0;
?>

<style>
    .bitacora h1{
        
    }

    .bitacora h2{
        padding: 3px;
    }

    .bitacora h3{
        
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
        <table class="table bg-white" style="max-width: 650px;">
            <thead>
                <th>Elemento</th>
                <th>Desarrollo/Actividad</th>
                <th>Precio</th>
            </thead>
            <tbody>
                <?php foreach ( $bitacora->result() as $row_bitacora ) { ?>
                    <?php
                        $total_cost += $row_bitacora->costo;
                    ?>
                    <tr>
                        <td><?php echo $row_bitacora->elemento ?></td>
                        <td><?php echo $row_bitacora->nombre_post ?></td>
                        <td class="text-right">$<?php echo number_format($row_bitacora->costo, 0) ?></td>
                    </tr>
                <?php } ?>
                <tr class="table-info">
                    <td>Total</td>
                    <td></td>
                    <td class="text-right">$<?php echo number_format($total_cost, 0) ?></td>
                </tr>
            </tbody>
        </table>

        <br>
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
                        <h2>TTAA--<?php echo $row_bitacora->modulo ?></h2>
                    <?php } ?>
                    <h3>
                        TTBB--<?php echo $row_bitacora->elemento ?>: <?php echo $row_bitacora->nombre_post ?>
                    </h3>
                    
                    <p>
                        <?php echo $this->Pcrn->fecha_formato($row_bitacora->fecha, 'Y-M-d') ?>
                    </p>
                    <?php echo $row_bitacora->contenido ?>
                    

                    <?php if ( $row_bitacora->costo > 0 ) { ?>
                        <p>
                            Costo: <b>$<?php echo number_format($row_bitacora->costo, 0) ?></b>
                        </p>
                    <?php } ?>
                <?php
                    $modulo_ant = $row_bitacora->modulo;
                ?>
                <?php } ?>
            </div>
        </div>
        
    </div>
</div>
