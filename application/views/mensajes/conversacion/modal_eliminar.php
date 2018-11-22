<!-- Modal -->
<div class="modal fade" id="modal_eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Eliminar conversación</h4>
            </div>
            <div class="modal-body">
                <p>
                    ¿Confirma que desea eliminar esta conversación y todos sus mensajes?
                </p>
            </div>
            <div class="modal-footer">
                <?= anchor("mensajes/abandonar/{$row->id}", 'Sí', 'class="btn btn-danger w3"') ?>
                <button type="button" class="btn btn-default w3" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>