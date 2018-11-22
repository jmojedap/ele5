<?php  

    //Totales
        $suma_cant_login = 0;
        $max_cant_login = 0;
        foreach ($usuarios->result() as $row_usuario){
            $suma_cant_login += $row_usuario->cant_login;
            if ( $row_usuario->cant_login > $max_cant_login ) { $max_cant_login = $row_usuario->cant_login; }
        }

        $avg_cant_login = $this->Pcrn->dividir($suma_cant_login, $usuarios->num_rows());
        
    //Promedio
        $avg_porcentaje = $this->Pcrn->int_percent($avg_cant_login, $max_cant_login);
    
?>

<?php $this->load->view($vista_submenu); ?>

<div class="row">
    <div class="col col-md-9">
        <table class="table table-hover bg-blanco" cellspacing="0">
            <thead>
                <th>Usuario</th>
                <th>Cantidad login</th>
                <th>Rol</th>
            </thead>
            
            <tbody>
                <tr>
                    <td class="resaltar"><b>Promedio</b></td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?= $avg_porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: <?= $avg_porcentaje ?>%">
                                <?= number_format($avg_cant_login,1) ?>
                            </div>
                        </div>
                    </td>
                    <td></td>
                </tr>

                <?php foreach ($usuarios->result() as $row_usuario) : ?>
                    <?php
                        $nombre_usuario = $this->App_model->nombre_usuario($row_usuario->usuario_id, 2);
                        $link_usuario = anchor("usuarios/actividad/{$row_usuario->usuario_id}", $nombre_usuario);

                        $porcentaje = $this->Pcrn->int_percent($row_usuario->cant_login, $max_cant_login);
                        
                        $clase_barra = '';
                        if ( $row_usuario->cant_login < $avg_cant_login ) { $clase_barra = 'progress-bar-warning'; }
                    ?>

                    <tr>
                        <td><?= $link_usuario ?></td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar <?= $clase_barra ?>" role="progressbar" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: <?= $porcentaje ?>%">
                                    <?= $row_usuario->cant_login ?>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $this->Item_model->nombre(58, $row_usuario->rol_id) ?></td>
                    </tr>

                <?php endforeach ?>


                <tr class="info">
                    <td><b>Total</b></td>
                    <td><?= $suma_cant_login ?></td>
                    <td></td>
                </tr>
            </tbody>

        </table>
        
        <table class="table table-default bg-blanco">
            <tbody>
                <?php $this->load->view('estadisticas/resumen_filtros_v'); ?>
            </tbody>
        </table>
    </div>
      
    <div class="col col-md-3">
        <?php if ( count($campos_filtros) > 0 ) { ?>
            <?php $this->load->view('estadisticas/form_filtros_v'); ?>
        <?php } ?>
    </div>
</div>



