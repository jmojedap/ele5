<?php
    $total_cost = 0;
?>

<table class="table bg-white" style="max-width: 650px;">
    <thead>
        <th>Módulo</th>
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
                <td><?php echo $row_bitacora->modulo ?></td>
                <td><?php echo $row_bitacora->modulo ?></td>
                <td><?php echo $row_bitacora->nombre_post ?></td>
                <td class="text-right">$<?php echo number_format($row_bitacora->costo, 0) ?></td>
            </tr>
        <?php } ?>
        <tr class="table-info">
            <td>Total</td>
            <td></td>
            <td></td>
            <td class="text-right">$<?php echo number_format($total_cost, 0) ?></td>
        </tr>
    </tbody>
</table>