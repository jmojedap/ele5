<!-- Modal -->
<div class="modal fade" id="modal_eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Eliminar registro</h4>
            </div>
            <div class="modal-body">
                <p>
                    ¿Confirma que desea eliminar este elemento?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger w3" data-dismiss="modal" id="eliminar_elemento" v-on:click="eliminar_elemento">Sí</button>
                <button type="button" class="btn btn-default w3" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>