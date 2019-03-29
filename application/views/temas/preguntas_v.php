<?php
    $num_registro = 0;
?>

<div class="sep1">
    <?php echo anchor("temas/agregar_pregunta/{$row->id}/0/add", 'Insertar pregunta al inicio', 'class="btn btn-default"') ?>
    <?php echo anchor("temas/agregar_pregunta/{$row->id}/{$preguntas->num_rows()}/add", 'Insertar pregunta al final', 'class="btn btn-default"') ?>
</div>



    <?php foreach ($preguntas->result() as $row_pregunta) : ?>
        <?php
            $num_subir = $num_registro - 1;
            $num_bajar = $num_registro + 1;
            $orden_mostrar = $row_pregunta->orden + 1;
            $num_siguiente = $num_registro + 1;
        ?>

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                Pregunta <?php echo $orden_mostrar ?>
                <div class="pull-right">
                    <?php echo anchor("preguntas/detalle/{$row_pregunta->id}", 'Ver detalle', 'class="btn btn-default"') ?>
                    <?php echo anchor("temas/mover_pregunta/{$row->id}/{$row_pregunta->id}/{$num_subir}", '<i class="fa fa-caret-up"></i>', 'class="btn btn-default"') ?>
                    <?php echo anchor("temas/mover_pregunta/{$row->id}/{$row_pregunta->id}/{$num_bajar}", '<i class="fa fa-caret-down"></i>', 'class="btn btn-default"') ?>
                    <?php echo $this->Pcrn->anchor_confirm("temas/quitar_pregunta/{$row->id}/{$row_pregunta->id}", '<i class="fa fa-times"></i> Quitar', 'class="btn btn-default" title="Quitar pregunta de este tema, no se elimina"', 'La pregunta no se eliminará, sólo se quitará del tema, ¿Desea continuar?') ?>
                </div>
            </div>
            <div class="panel-body">
                <?php if ( ! is_null($row_pregunta->enunciado_id) ){ ?>
                    <?php 
                        $titulo_enunciado = $this->App_model->nombre_enunciado($row_pregunta->enunciado_id);
                    ?>
                    <p>
                        <span class="suave">Lectura asociada:</span>
                        <?php echo anchor("datos/enunciados/read/{$row_pregunta->enunciado_id}", $titulo_enunciado, 'target="_blank"') ?>
                    </p>
                <?php } ?>

                <p><?php echo $row_pregunta->texto_pregunta ?></p>
                <p><?php echo $row_pregunta->enunciado_2 ?></p>
                
                <table class="tabla-transparente">
                    <tbody>
                        <tr>
                            <td class="td1" width="40px">A</td>
                            <td class="td1 izq"><?php echo $row_pregunta->opcion_1 ?></td>
                        </tr>
                        <tr>
                            <td class="td1">B</td>
                            <td class="td1 izq"><?php echo $row_pregunta->opcion_2 ?></td>
                        </tr>
                        <tr>
                            <td class="td1">C</td>
                            <td class="td1 izq"><?php echo $row_pregunta->opcion_3 ?></td>
                        </tr>
                        <tr>
                            <td class="td1">D</td>
                            <td class="td1 izq"><?php echo $row_pregunta->opcion_4 ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="panel-footer">
                <span class="suave">Competencia: </span>
                <span class="resaltar"><?php echo $this->App_model->nombre_item($row_pregunta->competencia_id) ?></span> | 
                <span class="suave">Creado por: </span>
                <span class="resaltar"><?php echo $this->App_model->nombre_usuario($row_pregunta->creado_usuario_id, 2) ?></span>
            </div>
        </div>

        <?php $num_registro += 1; //Siguiente fila ?>

        <div class="sep1">
            <?php echo anchor("temas/agregar_pregunta/{$row->id}/{$num_siguiente}/add", 'Insertar pregunta aquí', 'class="btn btn-default"') ?>
        </div>
    <?php endforeach ?>