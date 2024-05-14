<?php

//Clases columnas
    $clases_col['selector'] = '';
    $clases_col['tipo'] = '';

    $clases_col['no_documento'] = '';
    $clases_col['rol'] = '';

    if ($this->session->userdata('rol_id') >= 3) 
    {
        $clases_col['selector'] = '';
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
        <th width="20px" class="warning">ID</th>
        <th>Post</th>
        <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
    </thead>

    <tbody>
        <?php foreach ($resultados->result() as $row_resultado) { ?>
            <?php
                //Variables
                $nombre_elemento = $this->Pcrn->si_strlen($row_resultado->nombre_post, 'Post ' . $row_resultado->id);
                $destino_elemento = "posts/index/{$row_resultado->id}";
                $link_elemento = anchor($destino_elemento, $nombre_elemento);
            ?>
            <tr id="fila_<?= $row_resultado->id ?>">
                <td>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_registro" value="1" data-id="<?= $row_resultado->id ?>">
                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                        </label>
                    </div>
                </td>
                <td class="warning"><?= $row_resultado->id ?></td>
                <td><?= $link_elemento ?></td>

                <td class="<?= $clases_col['tipo'] ?>">
                    <?= $arr_tipos[$row_resultado->tipo_id] ?>
                </td>
            </tr>
        <?php } //foreach ?>
    </tbody>
</table>

