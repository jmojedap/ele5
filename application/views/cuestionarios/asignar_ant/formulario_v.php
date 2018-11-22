<?php
    //Selección de grupos
        $condicion_grupos = "nivel = {$row->nivel} AND institucion_id = {$institucion_id}";
    
        if ( $this->session->userdata('srol') != 'interno' ) 
        {
            $str_grupos = '0';
            $arr_grupos = $this->session->userdata('arr_grupos');
            if ( count($arr_grupos) > 0 ) { $str_grupos = implode(',', $arr_grupos); }
            $condicion_grupos = 'grupo.id IN (' . $str_grupos . ')';
        }
        
    $opciones_grupos = $this->App_model->opciones_grupo($condicion_grupos, 1);
?>

<form id="formulario" @submit.prevent="enviar_formulario" accept-charset="utf-8">

    <div class="modal fade" tabindex="-1" role="dialog" id="modal_formulario">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Programar cuestionario</h4>
                </div>
                <div class="modal-body">

                        <div class="form-group row">
                            <label for="Grupo" class="col-md-4 control-label">
                                <span class="float-right">Grupo</span>
                            </label>
                            <div class="col-md-8">
                                <?php echo form_dropdown('grupo_id', $opciones_grupos, $grupo_id, 'name="grupo_id" v-model="valores_form.grupo_id" class="form-control" required') ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tiempo_minutos" class="col-md-4 control-label">
                                Tiempo minutos
                            </label>
                            <div class="col-md-8">
                                <input
                                    id="campo-tiempo_minutos"
                                    name="tiempo_minutos"
                                    class="form-control"
                                    type="number"
                                    min="1"
                                    placeholder="Minutos"
                                    title="Minutos para responder el cuestionario"
                                    required
                                    v-model="valores_form.tiempo_minutos"
                                    >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fecha_inicio" class="col-md-4 control-label">
                                <span class="float-right">Desde | Hasta</span>
                            </label>
                            <div class="col-md-4">
                                <input
                                    id="campo-fecha_inicio"
                                    name="fecha_inicio"
                                    class="form-control bs_datepicker"
                                    placeholder="AAAA-MM-DD"
                                    title="Fecha inicial para responder cuestionario"
                                    required
                                    v-model="valores_form.fecha_inicio"
                                    >
                            </div>
                            
                            <div class="col-md-4">
                                <input
                                    id="campo-fecha_fin"
                                    name="fecha_fin"
                                    class="form-control bs_datepicker"
                                    placeholder="AAAA-MM-DD"
                                    title="Fecha final para responder cuestionario"
                                    required
                                    v-model="valores_form.fecha_fin"
                                    >
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-success" type="submit">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
