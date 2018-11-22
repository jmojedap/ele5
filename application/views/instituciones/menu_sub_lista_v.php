<?php
    $cuestionario_ant = 0;
    $pre_link = "instituciones/resultados_lista/{$row->id}";
    $att_link = 'class="num_opcion"';
?>

<div class="div2" style="width: 49%; float: left;">
    <h3>Cuestionario</h3>
    <?php foreach ($cuestionarios_grupos->result() as $row_cg): ?>
        <?php
            $texto = $this->App_model->nombre_cuestionario($row_cg->cuestionario_id, 1);
            $link = "{$pre_link}/{$row_cg->cuestionario_id}/{$row_cg->grupo_id}";
            $clase = 'a3';
            if ( $cuestionario_id == $row_cg->cuestionario_id ){
                $clase = 'a3 actual';
            }
        ?>

        <?php if ( $row_cg->cuestionario_id != $cuestionario_ant  ):?>
                <?= anchor($link, $texto, 'class="'. $clase .'"') ?>
        <?php endif ?>

        <?php 
            //Para comparación en siguiente ciclo
            $cuestionario_ant = $row_cg->cuestionario_id            
        ?>

    <?php endforeach; ?>
</div>

<div class="div2" style="width: 49%; float: left">
    <h3>Grupos</h3>
    <?php foreach ($cuestionarios_grupos->result() as $row_cg): ?>
        <?php
            $texto = $this->App_model->nombre_grupo($row_cg->grupo_id, 1);
            $link = "{$pre_link}/{$row_cg->cuestionario_id}/{$row_cg->grupo_id}";
            $clase = 'a3';
            if ( $grupo_id == $row_cg->grupo_id ){
                $clase = 'a3 actual';
            }
        ?>

        <?php if ( $row_cg->cuestionario_id == $cuestionario_id  ):?>
            <?= anchor($link, $texto, 'class="' . $clase . '" title""') ?>
        <?php endif ?>

        <?php 
            //Para comparación en siguiente ciclo
            $cuestionario_ant = $row_cg->cuestionario_id            
        ?>

    <?php endforeach; ?>
</div>