<!-- Modal -->
<div class="modal fade" id="modal_schedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form accept-charset="utf-8" method="POST" id="schedule_form" @submit.prevent="send_schedule_form">
    <input type="hidden" name="referente_id" v-model="element.id">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Programar link</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-group row">
                <label for="fecha_inicio" class="col-md-4 col-form-label text-right">Fecha</label>
                <div class="col-md-8">
                  <input
                    type="date"
                    id="field-fecha_inicio"
                    name="fecha_inicio"
                    required
                    class="form-control"
                    title="Fecha programada"
                    >
                </div>
              </div>

              <div class="form-group row">
                <label for="grupo_id" class="col-md-4 col-form-label text-right">Grupo</label>
                <div class="col-md-8">
                  <?php echo form_dropdown('grupo_id', $options_grupo, '', 'class="form-control" required v-model="group_id"') ?>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a v-bind:href="`<?php echo base_url("eventos/calendario/?tp=05&g=") ?>` + group_id" class="btn btn-success" style="display: none;" id="btn_calendar" target="_blank">
                Ver en calendario
            </a>
            <button type="submit" class="btn btn-primary w120p">Programar</button>
            <button type="button" class="btn btn-secondary w120p" data-dismiss="modal" v-on:click="clean_schedule_form">Cerrar</button>
          </div>
        </div>
      </div>
  </form>
</div>