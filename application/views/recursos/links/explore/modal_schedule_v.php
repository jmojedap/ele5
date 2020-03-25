<!-- Modal -->
<div class="modal fade" id="modal_schedule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form accept-charset="utf-8" method="POST" id="schedule_form" @submit.prevent="send_form">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Programar link</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Formulario de programación
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary w120p" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary w120p">Programar</button>
          </div>
        </div>
      </div>
  </form>
</div>