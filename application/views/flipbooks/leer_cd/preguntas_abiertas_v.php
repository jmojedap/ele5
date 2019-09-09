<!-- Modal -->
<div class="modal fade" id="modal_pa" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
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
                <div class="list-group">
                    <button
                        class="list-group-item list-group-item-action"
                        v-for="pregunta in data.preguntas_abiertas"
                        v-show='num_pagina == pregunta.num_pagina'
                    >
                        {{ pregunta.text_pregunta }}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Asignar</button>
            </div>
        </div>
    </div>
</div>