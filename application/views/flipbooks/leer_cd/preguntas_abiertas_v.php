<?php
    $options_group = array();
    foreach ($this->session->arr_grupos as $grupo_id) 
    {
        $options_group['0' . $grupo_id] = $this->App_model->nombre_grupo($grupo_id);
    }
?>

<!-- Modal -->
<div class="modal fade" id="modal_pa" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form accept-charset="utf-8" method="POST" id="pa_form" @submit.prevent="asignar_pa">
            
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Selecione una pregunta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Elija una pregunta para asignar a sus estudiantes:
                    </p>
                    <div class="list-group mb-2">
                        <button
                            type="button"
                            class="list-group-item list-group-item-action"
                            v-for="pregunta in data.preguntas_abiertas"
                            v-show='pagina.tema_id == pregunta.tema_id'
                            v-on:click="seleccionar_pregunta(pregunta.id)"
                            v-bind:class="{active: pregunta_id == pregunta.id}"
                        >
                            {{ pregunta.text_pregunta }}
                        </button>
                    </div>

                    <div class="form-group row">
                        <label for="grupo_id" class="col-md-4 col-form-label">Asignar al grupo</label>
                        <div class="col-md-8">
                            <?php echo form_dropdown('grupo_id', $options_group, '00', 'class="form-control" v-model="grupo_id" required') ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>