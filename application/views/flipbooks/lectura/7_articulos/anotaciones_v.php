<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAnotacion" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Escribe y participa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="alert alert-info" v-for="preguntaAbiertaAsignada in preguntasAbiertasAsignadas"
            v-show="preguntaAbiertaAsignada.tema_id == currentArticulo.tema_id">
            <p>
                {{ preguntaAbiertaAsignada.texto_pregunta }}
            </p>
        </div>
        <!-- RESPUESTA ESTUDIANTE -->
        <div v-show="anotacion.calificacion > 0" class="">
            <p><strong>Tú escribiste:</strong></p>
            <p class="fst-italic p-2 border rounded">
                {{ anotacion.anotacion }}
            </p>
            <p></p>
            <p>
                <strong class="me-2">Calificación:</strong>
                <i class="star fa-star" v-bind:class="starClass(anotacion.calificacion, 1)"></i>
                <i class="star fa-star" v-bind:class="starClass(anotacion.calificacion, 2)"></i>
                <i class="star fa-star" v-bind:class="starClass(anotacion.calificacion, 3)"></i>
                <i class="star fa-star" v-bind:class="starClass(anotacion.calificacion, 4)"></i>
                <i class="star fa-star" v-bind:class="starClass(anotacion.calificacion, 5)"></i>
            </p>
        </div>

        <form accept-charset="utf-8" id="anotacion-form" @submit.prevent="guardarAnotacion"
            v-show="anotacion.calificacion == 0">
            <div class="mb-2">
                <textarea rows="7" name="anotacion" class="form-control" required
                    v-model="anotacion.anotacion"></textarea>
                <span class="form-text">Escribe aquí una anotación sobre este tema o responde la pregunta
                    asignada</span>
            </div>
            <div class="mb-2">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-save"></i>
                    Guardar
                </button>
            </div>
        </form>

        <?php if ( $es_profesor ) : ?>
            <p class="mt-3">Ir a notas de estudiantes sobre este libro:</p>

            <div class="list-group">
                <a v-for="grupo in misGrupos" v-bind:href="`<?= URL_APP . "grupos/anotaciones/" ?>` + grupo.cod + `/` + flipbook.id" href="_blank"
                    class="list-group-item list-group-item-action"
                >
                    {{ grupo.name }}
                </a>
            </div>

        <?php else: ?>
            <a href="<?= URL_APP . "usuarios/anotaciones/" . $this->session->userdata('user_id') . '/' . $row->id ?>" href="_blank">
                Ver todas mis notas
            </a>
        <?php endif; ?>

    </div>
</div>