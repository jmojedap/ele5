<?php
    //Para clasificar los grupos por niveles
    $nivel_ant = -1;
    
    //Para clasificar los grupos por años de generación
    $anio_ant = -1;
?>


<div class="module_content">
    
    <?php if ( $grupos != FALSE ): //Verificar que tenga al menos un grupo?>
        <?php foreach ($grupos->result() as $row_grupo): ?>
            <?php
                //variables
                $destino = "instituciones/estudiantes/{$row->id}/{$row_grupo->id}";
                $texto = $row_grupo->nivel . " - " . $row_grupo->grupo ;
                if ( $grupo_id == $row_grupo->id ){
                    $clase = 'class="a2 seleccionado"';
                } else {
                    $clase = 'class="a2"';
                }

            ?>
            
            <?php if ( $row_grupo->anio_generacion != $anio_ant ):?>
                <h2>Grupos año <?= $row_grupo->anio_generacion ?></h2>
            <?php endif ?>
        
            <?php if ( $row_grupo->nivel != $nivel_ant ):?>
                <h4><?= $this->Item_model->nombre(3, $row_grupo->nivel) ?></h4>
            <?php endif ?>
            <?= anchor($destino, $texto, $clase) ?>
                
            <?php $anio_ant = $row_grupo->anio_generacion ?>
            <?php $nivel_ant = $row_grupo->nivel ?>
            
        <?php endforeach; //Recorriendo grupos ?>
    <?php endif ?>
</div>

