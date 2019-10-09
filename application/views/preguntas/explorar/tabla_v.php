<?php
    //Clases columnas
        $col_cl['nivel_area'] = 'd-none d-sm-table-cell d-lg-table-cell';
        $col_cl['tipo'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['texto_pregunta'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['descripcion'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['edicion'] = 'd-none d-lg-table-cell d-xl-table-cell';
?>

<table class="table bg-white" cellspacing="0">
    <thead>
            <tr class="">
                <th width="10px">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="check_todos" name="check_todos">
                        <label class="custom-control-label" for="check_todos">
                            <span class="text-hide">-</span>
                        </label>
                    </div>
                </th>
                <th width="50px;">ID</th>
                
                <th></th>
                <th class="<?php echo $col_cl['texto_pregunta'] ?>">Texto pregunta</th>
                <th class="<?php echo $col_cl['nivel_area'] ?>" style="min-width: 200px;">Nivel Área</th>
                <th class="<?php echo $col_cl['descripcion'] ?>"></th>
                <th class="<?php echo $col_cl['edicion'] ?>">Editado por</th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = "Pregunta " . $row_resultado->id;
                    $link_elemento = anchor("preguntas/index/$row_resultado->id", 'Ver');
            ?>
                <tr id="fila_<?php echo $row_resultado->id ?>">
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input check_registro" data-id="<?php echo $row_resultado->id ?>" id="check_<?php echo $row_resultado->id ?>">
                            <label class="custom-control-label" for="check_<?php echo $row_resultado->id ?>">
                                <span class="text-hide">-</span>
                            </label>
                        </div>
                    </td>
                    
                    <td class="table-warning"><?php echo $row_resultado->id ?></td>
                    
                    <td>
                        <a href="<?php echo base_url("preguntas/index/{$row_resultado->id}") ?>" class="btn btn-primary">
                            Ver
                        </a>
                    </td>

                    <td class="<?php echo $col_cl['texto_pregunta'] ?>">
                        <?php echo word_limiter($row_resultado->texto_pregunta, 20) ?>
                        <?php if ( $row_resultado->version_id > 0 ) { ?>
                            <br>
                            <a href="<?php echo base_url("preguntas/editar_version/{$row_resultado->id}") ?>"
                                class="btn btn-danger btn-sm"
                                title="Tiene versión con cambios propuestos"
                                target="_blank"
                                >
                                <i class="fa fa-exclamation-triangle"></i> Versión
                            </a>
                        <?php } ?>
                    </td>

                    
                    <td class="<?php echo $col_cl['nivel_area'] ?>">
                        <span class="etiqueta nivel w1"><?php echo $row_resultado->nivel ?></span>
                        <?php echo $this->App_model->etiqueta_area($row_resultado->area_id) ?>
                    </td>
                    
                    <td class="<?php echo $col_cl['descripcion'] ?>">
                        <span class="text-muted">Tipo:</span>
                        <?php echo $arr_tipos[$row_resultado->tipo_pregunta_id] ?>
                        
                        
                    </td>
                    
                    <td class="<?php echo $col_cl['edicion'] ?>">
                        <?php echo $this->App_model->nombre_usuario($row_resultado->editado_usuario_id, 2); ?>
                        <br/>
                        <span class="text-muted" title="<?php echo $row_resultado->editado ?>">
                            <?php echo $this->Pcrn->fecha_formato($row_resultado->editado, 'M-d') ?> &middot;
                            <?php echo $this->Pcrn->tiempo_hace($row_resultado->editado, TRUE) ?>
                        </span>
                    </td>
                </tr>

            <?php } //foreach ?>
    </tbody>
</table>  