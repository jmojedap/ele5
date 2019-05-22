<?php
    //Clases columnas
        $col_cl['nivel_area'] = 'd-none d-sm-table-cell d-lg-table-cell';
        $col_cl['tipo'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['texto_pregunta'] = 'd-none d-md-table-cell d-lg-table-cell';
        $col_cl['edicion'] = 'd-none d-lg-table-cell d-xl-table-cell';
        
    //Arrays con valores para contenido en lista
        $arr_tipos = $this->Item_model->arr_interno('categoria_id = 161');
        $arr_nivel = $this->Item_model->arr_interno('categoria_id = 3');

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
                <th>Pregunta</th>
                
                <th class="<?php echo $col_cl['texto_pregunta'] ?>">Texto pregunta</th>
                <th class="<?php echo $col_cl['nivel_area'] ?>" style="min-width: 200px;">Nivel Área</th>
                <th class="<?php echo $col_cl['edicion'] ?>">Editado por</th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_elemento = "Pregunta " . $row_resultado->id;
                    $link_elemento = anchor("preguntas/index/$row_resultado->id", $nombre_elemento);
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
                    
                    <td><?php echo $row_resultado->id ?></td>
                    
                    <td>
                        <?php echo $link_elemento ?>
                    </td>
                    <td class="<?php echo $col_cl['texto_pregunta'] ?>">
                        <?php echo word_limiter($row_resultado->texto_pregunta, 20) ?>
                    </td>

                    <td class="<?php echo $col_cl['nivel_area'] ?>">
                        <span class="etiqueta nivel w1"><?php echo $row_resultado->nivel ?></span>
                        <?php echo $this->App_model->etiqueta_area($row_resultado->area_id) ?>
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