<?php
    $num_registro = 0;
?>

<div class="sep1">
    <?php echo anchor("temas/agregar_pregunta/{$row->id}/0/add", 'Insertar pregunta al inicio', 'class="btn btn-light"') ?>
    <?php echo anchor("temas/agregar_pregunta/{$row->id}/{$preguntas->num_rows()}/add", 'Insertar pregunta al final', 'class="btn btn-light"') ?>
</div>



    <?php foreach ($preguntas->result() as $row_pregunta) : ?>
        <?php
            $num_subir = $num_registro - 1;
            $num_bajar = $num_registro + 1;
            $orden_mostrar = $row_pregunta->orden + 1;
            $num_siguiente = $num_registro + 1;
        ?>

        <div class="card card-default">
            <div class="card-header clearfix">
                Pregunta <?php echo $orden_mostrar ?>
                <div class="float-right">
                    <a href="<?php echo base_url("preguntas/detalle/{$row_pregunta->id}") ?>" class="btn btn-sm btn-secondary">
                        Ver detalle
                    </a>
                    <a href="<?php echo base_url("temas/mover_pregunta/{$row->id}/{$row_pregunta->id}/{$num_subir}") ?>" class="btn btn-sm btn-secondary">
                        <i class="fa fa-caret-up"></i>
                    </a>
                    <a href="<?php echo base_url("temas/mover_pregunta/{$row->id}/{$row_pregunta->id}/{$num_bajar}") ?>" class="btn btn-sm btn-secondary">
                        <i class="fa fa-caret-down"></i>
                    </a>

                    <a
                        href="<?php echo base_url("temas/quitar_pregunta/{$row->id}/{$row_pregunta->id}") ?>"
                        class="btn btn-sm btn-warning" title="Quitar pregunta de este tema, no se elimina"
                        onclick="return confirm ('La pregunta no se eliminará, sólo se quitará del tema, ¿Desea continuar?');"
                        >
                        <i class="fa fa-times"></i> Quitar
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                            <td class="" width="40px">A</td>
                            <td class=""><?php echo $row_pregunta->opcion_1 ?></td>
                        </tr>
                        <tr>
                            <td class="">B</td>
                            <td class=""><?php echo $row_pregunta->opcion_2 ?></td>
                        </tr>
                        <tr>
                            <td class="">C</td>
                            <td class=""><?php echo $row_pregunta->opcion_3 ?></td>
                        </tr>
                        <tr>
                            <td class="">D</td>
                            <td class=""><?php echo $row_pregunta->opcion_4 ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer">
                <span class="suave">Competencia: </span>
                <span class="resaltar"><?php echo $this->App_model->nombre_item($row_pregunta->competencia_id) ?></span> | 
                <span class="suave">Creado por: </span>
                <span class="resaltar"><?php echo $this->App_model->nombre_usuario($row_pregunta->creado_usuario_id, 2) ?></span>
            </div>
        </div>

        <?php $num_registro += 1; //Siguiente fila ?>

        <div class="my-2">
            <?php echo anchor("temas/agregar_pregunta/{$row->id}/{$num_siguiente}/add", 'Insertar pregunta aquí', 'class="btn btn-secondary"') ?>
        </div>
    <?php endforeach ?>