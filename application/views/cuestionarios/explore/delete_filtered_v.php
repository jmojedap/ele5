<!-- Modal -->
<div class="modal fade" id="delete_filtered_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail_modal_label">ELIMINACIÓN MASIVA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>
                    <i class="fa fa-info-circle text-warning fa-3x" v-show="!delete_process"></i>
                    <i class="fa fa-spin fa-spinner fa-3x" v-show="delete_process"></i>
                </p>
                <p>
                    Este proceso no podrá deshacerse. Se eliminarán <strong class="text-danger">{{ search_num_rows }}</strong> cuestionarios encontrados con los filtros actuales:
                </p>
                <p>
                    <div v-for="(filter, filter_key) in filters" v-show="filter">
                        <span class="text-muted">{{ filter_key }}:</span>
                        <b class="text-primary">{{ filter }}</b>
                    </div>
                </p>
                <p>
                    <input type="checkbox" v-model="delete_confirm">
                    Active la casilla para habilitar el botón [Continuar]
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger w100p" v-on:click="delete_filtered" v-bind:disabled="!delete_confirm" data-dismiss="modal">Continuar</button>
                <button type="button" class="btn btn-secondary w100p" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>