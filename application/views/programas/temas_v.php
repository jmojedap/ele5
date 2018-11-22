<?php
    $num_tema = 0;
    
?>

<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <tr>
            <th width="45px">Id</th>
            <th>Num</th>
            <th width="100px">Cód. tema</th>
            <th>Nombre tema</th>

            <th width="60px">Nivel</th>
            <th>Área</th>
            <th>Tipo</th>

            <?php if ( $this->session->userdata('rol_id') == 1 ) : ?>                
                <th>Editar</th>
            <?php endif ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($temas->result() as $row_tema){ ?>
            <tr>
                <td class="warning"><?= $row_tema->id ?></td>
                <td><?= $row_tema->orden + 1 ?></td>
                <td><?= $row_tema->cod_tema ?></td>
                <td><?= anchor("temas/archivos/{$row_tema->id}", $row_tema->nombre_tema) ?></td>
                <td><span class="etiqueta nivel w1"><?= $row_tema->nivel ?></span></td>
                <td>
                    <?= $this->App_model->etiqueta_area($row_tema->area_id) ?>
                </td>
                <td><?= $this->Item_model->nombre(17, $row_tema->tipo_id) ?></td>

                <?php if ( $this->session->userdata('rol_id') == 1 ) : ?>                
                    <td><?= anchor("temas/preguntas/{$row_tema->id}", 'Ver', 'class="a2"') ?></td>
                <?php endif ?>

            </tr>

        <?php } //foreach ?>
    </tbody>
</table>