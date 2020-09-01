<div class="modal fade" id="modal_eliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar conversación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                <p>
                    ¿Confirma que desea eliminar esta conversación y todos sus mensajes?
                </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary w120p" data-dismiss="modal">Cancelar</button>
        <a href="<?= base_url("mensajes/abandonar/{$row->id}") ?>" class="btn btn-danger w120p">
            Sí
        </a>
      </div>
    </div>
  </div>
</div>