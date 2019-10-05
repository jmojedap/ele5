<div class="modal" tabindex="-1" role="dialog" id="delete_file_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar archivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Confirma que desea eliminar este archivo?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btn_delete_file" v-on:click="delete_file" data-dismiss="modal">
                    Eliminar
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>