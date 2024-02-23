<?php
    $num_registro = 0;
?>

<div class="center_box_920">
    <div class="mb-2">
        <?php echo anchor("admin/temas/agregar_pregunta/{$row->id}/0/add", 'Insertar pregunta aquí', 'class="btn btn-light"') ?>
    </div>
    <?php foreach ($preguntas->result() as $row_pregunta) : ?>
        <?php
            $num_subir = $num_registro - 1;
            $num_bajar = $num_registro + 1;
            $orden_mostrar = $row_pregunta->orden + 1;
            $num_siguiente = $num_registro + 1;
        ?>
        <div class="card">
            <div class="card-header">
                Pregunta <?php echo $orden_mostrar ?>/<strong><?= $preguntas->num_rows() ?></strong>
                <div class="float-right">
                    <a href="<?php echo base_url("preguntas/detalle/{$row_pregunta->id}") ?>" class="btn btn-sm btn-secondary">
                        Ver detalle
                    </a>
                    <a href="<?php echo base_url("admin/temas/mover_pregunta/{$row->id}/{$row_pregunta->id}/{$num_subir}") ?>" class="btn btn-sm btn-secondary">
                        <i class="fa fa-caret-up"></i>
                    </a>
                    <a href="<?php echo base_url("admin/temas/mover_pregunta/{$row->id}/{$row_pregunta->id}/{$num_bajar}") ?>" class="btn btn-sm btn-secondary">
                        <i class="fa fa-caret-down"></i>
                    </a>

                    <a
                        href="<?php echo base_url("admin/temas/quitar_pregunta/{$row->id}/{$row_pregunta->id}") ?>"
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
                        <?php echo anchor("enunciados/explorar/read/{$row_pregunta->enunciado_id}", $titulo_enunciado, 'target="_blank"') ?>
                    </p>
                <?php } ?>

                <p><?php echo $row_pregunta->texto_pregunta ?></p>
                <p><?php echo $row_pregunta->enunciado_2 ?></p>
                
                <table class="tabla-transparente">
                    <tbody>
                        <tr>
                            <td width="30px">
                                <span class="badge badge-primary">A</span>
                            </td>
                            <td class=""><?php echo $row_pregunta->opcion_1 ?></td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-primary">B</span></td>
                            <td class=""><?php echo $row_pregunta->opcion_2 ?></td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-primary">C</span></td>
                            <td class=""><?php echo $row_pregunta->opcion_3 ?></td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-primary">D</span></td>
                            <td class=""><?php echo $row_pregunta->opcion_4 ?></td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                
                <div class="">
                    <span class="text-muted">Competencia: </span>
                    <span class="text-primary"><?php echo $this->App_model->nombre_item($row_pregunta->competencia_id) ?></span> &middot; 
                    <span class="text-muted">Creado por: </span>
                    <span class="text-primary"><?php echo $this->App_model->nombre_usuario($row_pregunta->creado_usuario_id, 2) ?></span>
                </div>
            </div>
            
        </div>

        <?php $num_registro += 1; //Siguiente fila ?>

        <div class="my-2">
            <?php echo anchor("admin/temas/agregar_pregunta/{$row->id}/{$num_siguiente}/add", 'Insertar pregunta aquí', 'class="btn btn-light"') ?>
        </div>
    <?php endforeach ?>
</div>
