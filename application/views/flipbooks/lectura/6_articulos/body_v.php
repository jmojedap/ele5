<div id="flipbookApp">
    <div class="contenido-layout">
        <div class="column-left">
            <?php $this->load->view('flipbooks/lectura/6_articulos/sidebar_v') ?>
        </div>
        <div class="column-center">
            <div class="articulo-container">
                <div class="articulo-tema">
                    <p class="text-center text-muted">{{ numPage }}</p>
                    <h1 class="articulo-titulo"><i class="fa fa-chevron-right"></i> {{ currentArticulo.titulo }}</h1>
                    <p class="subtitulo">{{ currentArticulo.subtitle }}</p>
                    <p class="epigrafe">{{ currentArticulo.resumen }}</p>
                    <div class="contenido" v-html="currentArticulo.contenido"></div>
                </div>
            </div>
        </div>
        <div class="column-right">

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAnotacion"
                aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel">Notas sobre este tema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <form accept-charset="utf-8" id="anotacion-form" @submit.prevent="guardarAnotacion"
                        v-show="anotacion.calificacion == 0">
                        <div class="mb-2">
                            <textarea rows="7" name="anotacion" class="form-control"
                                placeholder="Escribe aquí una anotación sobre este tema" required
                                v-model="anotacion.anotacion">
                            </textarea>
                        </div>
                        <div class="mb-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-2">
            </div>

            <?php $this->load->view('flipbooks/lectura/6_articulos/audios_modal_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos/animaciones_modal_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos/preguntas_abiertas_v') ?>
        </div>
    </div>
    <footer class="fixed-bottom footer d-flex justify-content-center">
        <?php if ( $es_profesor ) : ?>
        <button class="btn btn-light me-1" data-bs-toggle="modal" data-bs-target="#modalPreguntasAbiertas">
            <i class="fa-solid fa-pencil"></i>
            Escribe
        </button>
        <?php endif; ?>
        <button class="btn btn-light me-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAnotacion" aria-controls="offcanvasAnotacion">
            <i class="fa-regular fa-comment"></i>
            Notas
        </button>
        <div>
            <?php $this->load->view('flipbooks/lectura/6_articulos/audios_button_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos/animaciones_button_v') ?>
            <?php $this->load->view('flipbooks/lectura/6_articulos/quices_button_v') ?>
        </div>
    </footer>
</div>

<?php $this->load->view('flipbooks/lectura/6_articulos/vue_v') ?>