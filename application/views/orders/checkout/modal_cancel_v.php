<div class="modal" tabindex="-1" role="dialog" id="cancel_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abandonar compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Se vaciará el carrito de compras y se redirigirá al inicio</p>
                <p>¿Desea continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger w100p" v-on:click="cancel_order" data-dismiss="modal">
                    Continuar
                </button>
                <button type="button" class="btn btn-secondary w120p" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>