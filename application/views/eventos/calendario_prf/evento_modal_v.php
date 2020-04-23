<div class="modal" tabindex="-1" role="dialog" id="evento_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Programar enlace</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form accept-charset="utf-8" method="POST" id="evento_form">
                    <p>Haga clic en la fecha donde programará el enlace</p>
                    <div class="form-group">
                        <label for="url">Fecha</label>
                        <input type="text" id="field-fecha_inicio" name="fecha_inicio" required
                            class="form-control bs_datepicker" placeholder="AAAA-MM-DD" title="AAAA-MM-DD">
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="url" id="field-url" name="url" required class="form-control"
                            placeholder="Escriba la URL" title="Escriba la URL">
                    </div>
                    <div class="form-group">
                        <label for="grupo_id">Grupo</label>
                        <?= form_dropdown('grupo_id', $opciones_grupo, '', 'id="field-grupo_id" class="form-control" required title="Elija el grupo al cual le asigna el link"') ?>
                    </div>

                    <div class="float-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" type="submit">
                            Guardar
                        </button>
                    </div>

                    <button class="btn btn-warning eliminar_link" type="button">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>

                    <a id="link_evento_actual" href="#" target="_blank" class="btn btn-info" title="Abrir el link">
                        <i class="fa fa-external-link-alt"></i> Abrir
                    </a>
                </form>
            </div>

        </div>
    </div>
</div>