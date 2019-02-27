<?php $this->load->view('head_includes/respuesta_lote') ?>

<?php    
    $submit = array(
        'value' =>  'Guardar',
        'class' =>  'btn btn-primary'
    );
    
    $area_anterior = 0;
    $num_pregunta = 0;
    
    if ( $this->session->userdata('rol_id') == 7 ){
        $destino_cancelar = "cuestionarios/grupos/{$row->id}/{$row_usuario->grupo_id}";
    } else {
        $destino_cancelar = "cuestionarios/grupos/{$row->id}/{$row_usuario->grupo_id}";
    }
    
    $resumen = json_decode($row_uc->resumen, true);
    
    $resultados['total'] = $this->Cuestionario_model->resultado_usuario($row_uc->id);
    $resultados['a51'] = $this->Cuestionario_model->resultado_usuario($row_uc->id, 'a51');
    $resultados['c103'] = $this->Cuestionario_model->resultado_usuario($row_uc->id, 'c103');
    $resultados['p85'] = $this->Cuestionario_model->resultado_usuario($row_uc->id, 'p85');

?>

<p>
    <span class="suave"><i class="fa fa-bank"></i></span>
    <span class="resaltar"><?php echo $this->App_model->nombre_institucion($row_usuario->institucion_id, 1) ?></span> &middot;
    <span class="suave">Grupo: </span>
    <span class="resaltar"><?php echo $this->App_model->nombre_grupo($row_usuario->grupo_id, 1) ?></span> &middot;
    <span class="suave">Fecha respuesta: </span>
    <span class="resaltar"><?php echo $this->Pcrn->fecha_formato($row_uc->fin_respuesta, 'Y-M-d') ?></span> &middot;
    <span class="suave">Hora respuesta: </span>
    <span class="resaltar"><?php echo $this->Pcrn->fecha_formato($row_uc->fin_respuesta, 'H:i') ?></span> &middot;
    <span class="suave">Estado: </span>
    <span class="resaltar"><?php echo $this->Item_model->nombre(151, $row_uc->estado) ?></span> &middot;
    <span class="suave">Respondidas: </span>
    <span class="resaltar"><?php echo $row_uc->num_con_respuesta ?></span> &middot;
    <span class="suave">% Correctas: </span>
    <span class="resaltar"><?php echo $resultados['total']['porcentaje'] ?></span>
</p>

<?php if ( $this->session->flashdata('resultado') == 1 ){ ?>
    <div class="alert alert-success">
        <i class="fa fa-check"></i>
        Las respuestas del cuestionario fueron guardadas correctamente
    </div>
<?php } ?>
    
<?php echo form_open("cuestionarios/guardar_lote/{$row_uc->id}") ?>
    
<div class="mb-3">
    <?php echo anchor("cuestionarios/grupos/{$row_uc->cuestionario_id}/{$row_uc->institucion_id}/{$row_uc->grupo_id}", '<i class="fa fa-caret-left"></i> Listado', 'class="btn btn-default" title="Volver a la lista de cargue"') ?>
    <?php echo form_submit($submit) ?>    
</div>

<div class="card card-default">
    <div class="card-header">
        <?php echo $nombre_usuario ?>
    </div>
    <div class="card-body">
        <div class=" cuestionario">

            <?php for($i = 0; $i < count($respuestas); $i++): ?>

                <?php
                    //Variables ciclo for
                    $nombre_area = $this->Pcrn->campo_id('item', $preguntas->row($i)->area_id, 'item');

                    if ( $area_anterior == $preguntas->row($i)->area_id ){
                        //La misma área
                        $num_pregunta = $num_pregunta + 1;
                    } else {
                        //Cambio de un área a otra
                        $num_pregunta = 1;
                    }
                ?>

                    <div class="pregunta">
                    <a name="p_<?php echo ($i + 1); ?>"></a>
                        <span class="nombre_area"><?php echo $nombre_area; ?></span>
                        <span class="no_pregunta"><?php echo $num_pregunta; ?></span>

                        <?php /* info para visualizar la respuesta */ ?>
                        <span class="control <?php if($respuestas[$i] != 0) echo ' status_verde'; ?>">
                            <?php
                                if($respuestas[$i] == 0){
                                    echo "NR";
                                }
                                else{
                                    //echo $num_pregunta . ' = ';
                                    if($respuestas[$i] == 1) echo 'A';	
                                    if($respuestas[$i] == 2) echo 'B';	
                                    if($respuestas[$i] == 3) echo 'C';	
                                    if($respuestas[$i] == 4) echo 'D';	
                                }
                            ?>
                        </span> 

                        <?php /* radio invisible cuando la pregunta no ha sido contestada */ ?>
                        <span class="opcion"><input type="radio" name="pregunta_<?php echo ($i + 1); ?>" value="0" <?php if($respuestas[$i] == 0) echo 'checked="checked"'; ?> /> NR</span>

                        <?php /* radios de las opciones A, B, C, D */ ?>
                        <span class="opcion<?php if($respuestas[$i] == 1) echo ' respuesta'; ?>"><input type="radio" name="pregunta_<?php echo ($i + 1); ?>" value="1" <?php if($respuestas[$i] == 1) echo 'checked="checked"'; ?> /> A</span> 
                        <span class="opcion<?php if($respuestas[$i] == 2) echo ' respuesta'; ?>"><input type="radio" name="pregunta_<?php echo ($i + 1); ?>" value="2" <?php if($respuestas[$i] == 2) echo 'checked="checked"'; ?> /> B</span> 
                        <span class="opcion<?php if($respuestas[$i] == 3) echo ' respuesta'; ?>"><input type="radio" name="pregunta_<?php echo ($i + 1); ?>" value="3" <?php if($respuestas[$i] == 3) echo 'checked="checked"'; ?> /> C</span> 
                        <span class="opcion<?php if($respuestas[$i] == 4) echo ' respuesta'; ?>"><input type="radio" name="pregunta_<?php echo ($i + 1); ?>" value="4" <?php if($respuestas[$i] == 4) echo 'checked="checked"'; ?> /> D</span>

                    </div>

                    <?php 
                        //Variable para comparar en el siguiente ciclo
                        $area_anterior = $preguntas->row($i)->area_id;

                    ?>

            <?php endfor; ?>
        </div>
        
        <!--  Mostar errores de validación      -->
        <?php if ( validation_errors() ):?>
            <div class="section_content">
                <div class="alert alert-danger"><?php echo validation_errors() ?></div>
            </div>
        <?php endif ?>
        
    </div>
</div>
    
<?php echo form_close() ?>