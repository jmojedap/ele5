<?php
    $modulo_ant = '';

    $arr_estados_pago = array(
        1 => 'Pagado',
        2 => 'Pago pendiente',
        3 => 'Por facturar',
        4 => 'Finalizado',
        5 => 'En desarrollo',
        6 => 'Sin empezar'
    );
?>

<style>
    .bitacora h1{
        color: #333;
    }

    .bitacora h2{
        color: #1565c0;
        padding-left: 0px;
    }

    .bitacora h3{
        color: #89CB4E;
        padding-left: 1em;
    }

    .bitacora .bitacora_post{
        margin-bottom: 1em;
        border-bottom: 1px solid #CCC;
        padding-bottom: 1em;
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
                    <br>
                    <?php if ( $row_pago->estado_id == 1 ) { ?>
                        <i class="fa fa-check-circle text-success"></i>
                    <?php } ?>
                    <?php if ( $row_pago->estado_id == 3 ) { ?>
                        <i class="fa fa-exclamation-triangle text-warning"></i>
                    <?php } ?>
                    <?php if ( $row_pago->estado_id == 3 ) { ?>
                        <i class="fa fa-exclamation-triangle text-warning"></i>
                    <?php } ?>
                    <?php echo $arr_estados_pago[$row_pago->estado_id] ?> &middot;
                    <?php echo $row_pago->texto_1 ?>
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
                    <h3><?php echo $row->nombre_post ?></h3>
                    <p>
                        Estado: <?php echo $arr_estados_pago[$row->estado_id] ?>
                        &middot;
                        <?php echo $row->texto_1 ?>
                    </p>
                    <?php if ( $this->session->userdata('rol_id') == 0 ) { ?>
                        <p>
                            <a href="<?php echo base_url("posts/edit/{$pago_id}") ?>" class="btn btn-primary" target="_blank">
                                <i class="fa fa-pencil-alt"></i>
                                Editar
                            </a>
                        </p>
                    <?php } ?>
                </div>
                <?php foreach ( $bitacora->result() as $row_bitacora ) { ?>
                    <?php
                        $cl_costo = ( $row_bitacora->costo > 0) ? 'alert-info' : 'alert-success';
                    ?>
                    <div class="bitacora_post">
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
                        <a href="<?php echo base_url("posts/edit/{$row_bitacora->id}") ?>" class="btn btn-success btn-sm" target="_blank">Editar</a>
                    </div>
                <?php
                    $modulo_ant = $row_bitacora->modulo;
                ?>
                <?php } ?>
            </div>
        </div>
        
    </div>
</div>
