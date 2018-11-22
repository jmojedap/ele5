<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<div class="sep2">
    <div class="casilla">
        <h3>Nuevo quiz tipo <i class="fa fa-caret-right"></i> </h3>
    </div>
    <?php foreach ($arr_tipo_quiz as $key => $letra) : ?>
        <?php echo anchor("quices/crear/{$row->id}/{$key}", $letra, 'class="btn btn-default w2" title="Nuevo quiz tipo ' . $letra . '"') ?>
    <?php endforeach ?>
</div>
    
<!-- Lista de quices   -->
<table class="table table-default bg-blanco" cellspacing="0">
    <thead>
        <tr>

            <th width="45px" class="warning">Id</th>
            <th width="100px">Cód. quiz</th>
            <th>Nombre quiz</th>
            <th>Tipo</th>


            <?php if ( $this->session->userdata('rol_id') <= 1 ) : ?>                
                <th width="100px"></th>
            <?php endif ?>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($quices->result() as $row_quiz){ ?>
            <tr>
                <td class="warning"><?php echo $row_quiz->id ?></td>
                <td><?php echo $row_quiz->cod_quiz ?></td>
                <td><?php echo anchor("quices/ver/$row_quiz->id", $row_quiz->nombre_quiz) ?></td>

                <td><?php echo $this->Item_model->nombre(9, $row_quiz->tipo_quiz_id) . $row_quiz->tipo_quiz_id ?></td>

                <?php if ( $this->session->userdata('rol_id') <= 1 ) : ?>                
                    <td>
                        <?php echo anchor("quices/editar/edit/{$row_quiz->id}", '<i class="fa fa-pencil"></i>', 'class="a4"') ?>
                        <?php echo $this->Pcrn->anchor_confirm("temas/quitar_quiz/{$row->id}/{$row_quiz->id}", '<i class="fa fa-times"></i>', 'class="a4" title="Quitar evidencia de este tema"', 'Se retirará el quiz de este tema. No se elimina. ¿Desea continuar?') ?>
                        <?php echo $this->Pcrn->anchor_confirm("quices/eliminar/{$row_quiz->id}/{$row->id}", '<i class="fa fa-trash"></i>', 'class="a4" title="Eliminar evidencia"') ?>
                    </td>
                <?php endif ?>

            </tr>

        <?php } //foreach ?>
    </tbody>
</table>