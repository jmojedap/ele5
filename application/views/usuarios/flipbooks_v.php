<?php
    $funcion_destino = 'anotaciones';
    $att_link = '';
    if ( $this->session->userdata('rol_id') == 6 ){
        $funcion_destino = 'abrir_flipbook'; //Es estudiante
        $att_link = 'target="_blank"';
    }
    
    
    //Clases columna
        $clases_col['quitar_flipbook'] = '';
    
        if ( $this->session->userdata('usuario_id') > 2 ) {
            $clases_col['quitar_flipbook'] = 'hidden';
        }
    
?>


<div class="bs-caja-no-padding">
    <table class="table table-hover" cellspacing="0">
        <thead>
            <tr>
                <th>Contenido</th>
                <th>Anotaciones</th>
                <th class="<?= $clases_col['quitar_flipbook'] ?>" style="width: 35px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($flipbooks->result() as $row_flipbook): ?>
                <tr>
                    <td><?= anchor("flipbooks/{$funcion_destino}/{$row_flipbook->flipbook_id}", $row_flipbook->nombre_flipbook, $att_link) ?></td>
                    <td><?= anchor("usuarios/anotaciones/{$row->id}/{$row_flipbook->flipbook_id}/{$usuario_id}", "Ver", 'class="btn btn-default"') ?></td>
                    <td class="<?= $clases_col['quitar_flipbook'] ?>"><?= $this->Pcrn->anchor_confirm("usuarios/quitar_flipbook/{$row->id}/{$row_flipbook->flipbook_id}", '<i class="fa fa-times"></i>', 'class="a4" title=""') ?></td>
                </tr>
            <?php endforeach; //Recorriendo flipbooks ?>
        </tbody>
    </table>
</div>