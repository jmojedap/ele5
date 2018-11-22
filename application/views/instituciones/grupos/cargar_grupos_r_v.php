<?php $this->load->view('instituciones/grupos/submenu_grupos_v') ?>

<?php if ( $cargado ){ ?>
    <div class="alert alert-success">
        <i class="fa fa-info-circle"></i>
        Se crearon <?= $num_cargados ?> grupos
    </div>
<?php } else { ?>
    <div class="alert alert-danger"><?= $mensaje ?></div>
    <div class="div2">
        <?= anchor("instituciones/cargar_grupos/{$row->id}", '<i class="fa fa-arrow-left"></i> Volver', 'class="btn btn-default" title="Volver"') ?>
    </div>
<?php } ?>
    
<?php if ( $num_no_cargados > 0 ){ ?>
    <h4 class="alert_warning">No se crearon <?= $num_no_cargados ?> grupos</h4>
<?php } ?>
    
<?php if ( $cargado ){ ?>
    <table class="table table-hover bg-blanco" cellspacing="0">
        <thead>
            <tr>
                <th width="45px">Id</th>
                <th width="60px">Grupo</th>            
                <th>Año</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cargados as $grupo_id) { ?>
                <?php
                    $row_grupo = $this->Pcrn->registro_id('grupo', $grupo_id);
                ?>
                
                <tr>
                    <td><span class="etiqueta primario"><?= $row_grupo->id ?></span></td>
                    <td><?= anchor("grupos/estudiantes/{$row_grupo->id}", $row_grupo->nivel . ' - ' . $row_grupo->grupo , 'class="a2"') ?></td>
                    <td><?= $row_grupo->anio_generacion ?></td>
                    
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>