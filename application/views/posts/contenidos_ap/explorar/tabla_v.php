<?php

//Íconos
    $arr_iconos[1] = '<i class="fa fa-video-camera fa-2x" style="color: #4285F4;"></i>';
    $arr_iconos[2] = '<i class="fa fa-picture-o fa-2x" style="color: #FBBC05;"></i>';
    $arr_iconos[3] = '<i class="fa fa-youtube-play fa-2x" style="color: #FF0000;"></i>';
    $arr_iconos[4] = '<i class="fa fa-file-pdf-o fa-2x" style="color: #D42839;"></i>';
    $arr_iconos[5] = '<i class="fa fa-file fa-2x" style="color: #34A853;"></i>';
    $arr_iconos[6] = '<i class="fa fa-external-link fa-2x" style="color: #EA4335;"></i>';

//Clases columnas
    $clases_col['selector'] = '';
    $clases_col['id'] = '';
    $clases_col['tipo'] = 'hidden';
    $clases_col['resumen'] = '';
    $clases_col['categoria'] = 'hidden-sm hidden-xs';
    $clases_col['area'] = '';

    $clases_col['no_documento'] = '';
    $clases_col['rol'] = '';
    $clases_col['botones'] = '';

    if ( $this->session->userdata('rol_id') >= 3 ) 
    {
        $clases_col['selector'] = 'hidden';
        $clases_col['tipo'] = 'hidden';
        $clases_col['id'] = 'hidden';
        $clases_col['botones'] = 'hidden';
        $clases_col['categoria'] = 'hidden';
    }
?>

<table class="table table-default bg-blanco" role="tabpanel" cellspacing="0">
    <thead>
        <th class="<?= $clases_col['selector'] ?>" width="20px">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="check_todos" value="1">
                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                </label>
            </div>
        </th>
        <th width="20px" class="warning <?php echo $clases_col['id'] ?>">ID</th>
        <th></th>
        <th>Contenido</th>
        <th class="<?= $clases_col['resumen'] ?>">Resumen</th>
        <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
        <th class="<?= $clases_col['categoria'] ?>">Categoría</th>
        <th class="<?= $clases_col['botones'] ?>" width="35px"></th>
    </thead>

    <tbody>
        <?php foreach ($resultados->result() as $row_resultado) { ?>
            <?php
                //Variables
                $nombre_elemento = $this->Pcrn->si_strlen($row_resultado->nombre_post, 'Post ' . $row_resultado->id);
                $destino_elemento = "posts/ap_leer/{$row_resultado->id}";
                $link_elemento = anchor($destino_elemento, $nombre_elemento);
            ?>
            <tr id="fila_<?= $row_resultado->id ?>">
                <td class="<?= $clases_col['selector'] ?>">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_registro" value="1" data-id="<?= $row_resultado->id ?>">
                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        </label>
                    </div>
                </td>
                <td class="warning <?php echo $clases_col['id'] ?>"><?= $row_resultado->id ?></td>
                <td style="width: 45px;">
                    <a class="btn btn-default" href="<?php echo base_url($destino_elemento) ?>" title="<?php echo $arr_tipos_ap[$row_resultado->referente_3_id] ?>">
                        <?php echo $arr_iconos[$row_resultado->referente_3_id] ?>
                    </a>
                </td>
                <td>
                    <b><?php echo $link_elemento ?></b>
                    <br/>
                    <?php echo $this->App_model->etiqueta_area($row_resultado->referente_2_id); ?>
                </td>
                
                <td class="<?= $clases_col['resumen'] ?>">
                    <?= $row_resultado->resumen ?>
                </td>

                <td class="<?= $clases_col['tipo'] ?>">
                    <?= $arr_tipos_ap[$row_resultado->referente_3_id] ?>
                </td>
                
                <td class="<?= $clases_col['categoria'] ?>">
                    <?= $arr_categorias_ap[$row_resultado->referente_1_id] ?>
                </td>
                
                <td class="<?= $clases_col['botones'] ?>">
                    <a href="<?php echo base_url("posts/ap_editar/{$row_resultado->id}") ?>" class="btn btn-xs btn-default">
                        <i class="fa fa-pencil"></i>
                    </a>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>

